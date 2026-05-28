<?php

namespace App\Http\Controllers\User;

use App\Models\Kyc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class KycController extends Controller
{
    //check if kyc module is loaded
    public function __construct()
    {
        if (!moduleEnabled('kyc_module')) {
            abort(404);
        }
    }

    public function index()
    {
        $page_title = __('Verify Identity (KYC)');
        $countries = json_decode(file_get_contents(public_path('assets/json/countries.json')), true);
        $last_kyc = Kyc::where('user_id', auth()->id())->latest()->first();
        return view('templates.bento.blades.user.kyc', compact('page_title', 'countries', 'last_kyc'));
    }

    public function submitKyc(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'dob_day' => 'required|numeric',
            'dob_month' => 'required|numeric',
            'dob_year' => 'required|numeric',
            'phone' => 'required|string',
            'phone_code' => 'required|string',
            'document_type' => 'required|string|in:passport,id_card,drivers_license',
            'doc_front' => 'required|file|image|max:5120',
            'doc_back' => 'nullable|file|image|max:5120',
            'selfie' => 'required|file|image|max:5120',
            'address_line_1' => 'required|string',
            'city' => 'required|string',
            'zip' => 'required|string',
            'country' => 'required|string',
            'proof_address' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        // allow only if user has no kyc record or last attempt was rejected
        $last_kyc = Kyc::where('user_id', auth()->id())->latest()->first();
        if ($last_kyc && $last_kyc->status != 'rejected') {
            return response()->json([
                'success' => false,
                'message' => __('You have already submitted a KYC request. Please wait for the review.'),
            ]);
        }

        // clean up phone number
        $request->phone = str_replace([' ', '-', '+', '(', ')'], '', $request->phone);

        try {
            // Construct DOB
            $dob = $request->dob_year . '-' .
                str_pad($request->dob_month, 2, '0', STR_PAD_LEFT) . '-' .
                str_pad($request->dob_day, 2, '0', STR_PAD_LEFT);



            $frontPath = $request->file('doc_front')->store('kyc', 'public');

            $backPath = null;
            if ($request->hasFile('doc_back')) {
                $backPath = $request->file('doc_back')->store('kyc', 'public');
            }

            $selfiePath = $request->file('selfie')->store('kyc', 'public');
            $proofPath = $request->file('proof_address')->store('kyc', 'public');

            // Create KYC Record
            $kyc_record = new Kyc();
            $kyc_record->user_id = auth()->id();
            $kyc_record->date_of_birth = $dob;
            $kyc_record->phone = $request->phone;
            $kyc_record->phone_code = $request->phone_code;
            $kyc_record->country = $request->country;
            $kyc_record->address_line_1 = $request->address_line_1;
            $kyc_record->city = $request->city;
            $kyc_record->zip = $request->zip;
            $kyc_record->document_type = $request->document_type;
            $kyc_record->document_front = $frontPath;
            $kyc_record->document_back = $backPath;
            $kyc_record->selfie = $selfiePath;
            $kyc_record->proof_address = $proofPath;
            $kyc_record->save();
            $kyc_record->refresh();

            // update the user record
            $user = auth()->user();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->save();

            // notify user
            sendKycEmail(__('KYC Verification Started'), $kyc_record);

            // record activity
            $title = "KYC Verification Started"; //will be translated later in blade;
            $body = "Your KYC has been submitted successfully. Our team is currently reviewing the documents provided"; // translated later in blade
            recordNotificationMessage(auth()->user(), $title, $body);

            return response()->json([
                'success' => true,
                'message' => __('KYC submitted successfully.')
            ]);

        } catch (\Exception $e) {
            Log::error('KYC Submission Error', [
                'userId' => auth()->id(),
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('Failed to upload documents. Please try again.'),
            ], 500);
        }
    }
}