<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('client_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->text('review');
            $table->integer('rating')->default(5);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        $reviews = [
            [
                'name' => 'Alexander Novak',
                'role' => 'Private Equity Analyst',
                'review' => 'The matching engine is significantly faster than any retail terminal I\'ve used. The execution speed during high-volatility events is truly institutional.',
                'rating' => 5,
                'image' => 'reviewer_1.png'
            ],
            [
                'name' => 'Dr. Sarah Chen',
                'role' => 'Hedge Fund Manager',
                'review' => 'Integration of traditional assets with liquid crypto futures is seamless. Their compliance-first approach gives us the confidence to scale our strategies.',
                'rating' => 5,
                'image' => 'reviewer_2.png'
            ],
            [
                'name' => 'Jameson Vane',
                'role' => 'Serial Tech Investor',
                'review' => 'The platform doesn\'t just offer a platform; they offer a competitive advantage. The ROI calculator is spot on, and the transparency is refreshing in this space.',
                'rating' => 5,
                'image' => 'reviewer_3.png'
            ],
            [
                'name' => 'Michael Sterling',
                'role' => 'Compliance Officer',
                'review' => 'The regulatory framework and real-time audit logs are exceptional. It\'s rare to find a platform that prioritizes security without sacrificing performance.',
                'rating' => 5,
                'image' => null
            ],
            [
                'name' => 'Elena Rodriguez',
                'role' => 'Quantitative Trader',
                'review' => 'API documentation is clean and the latency is minimal. Integrating our algorithmic models was straightforward and the results have been consistent.',
                'rating' => 5,
                'image' => null
            ],
            [
                'name' => 'David Chang',
                'role' => 'Wealth Manager',
                'review' => 'Our clients appreciate the transparent fee structure and the diverse asset classes. The portfolio management tools are top-tier.',
                'rating' => 5,
                'image' => null
            ],
            [
                'name' => 'Sophia Loren',
                'role' => 'Venture Capitalist',
                'review' => 'The strategic vision of the leadership team is clearly reflected in the product. It’s a robust bridge between traditional and digital finance.',
                'rating' => 5,
                'image' => null
            ],
            [
                'name' => 'Marcus Thorne',
                'role' => 'Risk Strategist',
                'review' => 'Their risk mitigation protocols are the best I’ve seen. The platform handles extreme market conditions with impressive stability.',
                'rating' => 5,
                'image' => null
            ],
            [
                'name' => 'Isabella Gomez',
                'role' => 'Fintech Advisor',
                'review' => 'User experience is intuitive yet powerful. They’ve successfully demystified complex trading instruments for institutional-grade users.',
                'rating' => 5,
                'image' => null
            ],
            [
                'name' => 'Robert Miller',
                'role' => 'Asset Allocation Lead',
                'review' => 'The depth of liquidity across all pairs is remarkable. We’ve been able to execute large orders with minimal slippage consistently.',
                'rating' => 5,
                'image' => null
            ],
        ];

        foreach ($reviews as $review) {
            \App\Models\ClientReview::create($review);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_reviews');
    }
};
