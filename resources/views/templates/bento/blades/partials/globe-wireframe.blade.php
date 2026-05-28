<div class="relative w-full h-full flex items-center justify-center overflow-hidden">
    {{-- Loader --}}
    <div id="globe-loader"
        class="absolute inset-0 flex items-center justify-center z-20 text-accent-primary animate-pulse">
        <svg class="w-10 h-10 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
    </div>

    <canvas id="globe-canvas" class="z-10 opacity-0 transition-opacity duration-1000"></canvas>

    {{-- Background Glow --}}
    <div class="absolute inset-0 bg-accent-primary/5 blur-[100px] rounded-full transform scale-50 pointer-events-none">
    </div>
</div>

<script>
    (function() {
        const canvas = document.getElementById('globe-canvas');
        const ctx = canvas.getContext('2d');
        const loader = document.getElementById('globe-loader');

        // Configuration
        const config = {
            width: 800,
            height: 800,
            radius: 300,
            dotSize: 1.2,
            rotationSpeed: 0.003,
            colors: {
                dot: 'rgba(255, 255, 255, 0.4)', // Higher opacity for map dots
                activeNode: '#7c3aed', // Accent Primary (Purple)
                beam: 'rgba(59, 130, 246, 0.5)', // Blue beams
                beamHead: '#fff'
            }
        };

        // Resize Canvas
        function resize() {
            canvas.width = config.width;
            canvas.height = config.height;
            canvas.style.width = '100%';
            canvas.style.height = '100%';
            canvas.style.maxWidth = `${config.width}px`;
        }
        resize();

        // Tier 1 Financial Hubs (Lat/Lon)
        const hubs = [{
                name: "New York",
                lat: 40.7128,
                lon: -74.0060
            },
            {
                name: "London",
                lat: 51.5074,
                lon: -0.1278
            },
            {
                name: "Tokyo",
                lat: 35.6762,
                lon: 139.6503
            },
            {
                name: "Singapore",
                lat: 1.3521,
                lon: 103.8198
            },
            {
                name: "Frankfurt",
                lat: 50.1109,
                lon: 8.6821
            },
            {
                name: "Sydney",
                lat: -33.8688,
                lon: 151.2093
            },
            {
                name: "Dubai",
                lat: 25.2048,
                lon: 55.2708
            },
            {
                name: "Zurich",
                lat: 47.3769,
                lon: 8.5417
            },
            {
                name: "Hong Kong",
                lat: 22.3193,
                lon: 114.1694
            }
        ];

        // Beams
        let beams = [];
        let rotation = 0;
        let points = [];
        let mapImage = new Image();

        // Helper: Spherical to Cartesian
        function getSpherePoint(lat, lon, radius) {
            const phi = (90 - lat) * (Math.PI / 180);
            const theta = (lon + 180) * (Math.PI / 180); // Offset to align with standard map

            // Standard mapping
            const x = -(radius * Math.sin(phi) * Math.cos(theta));
            const z = (radius * Math.sin(phi) * Math.sin(theta));
            const y = (radius * Math.cos(phi));

            return {
                x,
                y,
                z
            };
        }

        // Generate Points from Image Data
        function processMapData() {
            const sampleW = 200; // Resolution of sampling
            const sampleH = 100;

            const offCanvas = document.createElement('canvas');
            offCanvas.width = sampleW;
            offCanvas.height = sampleH;
            const offCtx = offCanvas.getContext('2d');

            // Draw image to smaller canvas
            offCtx.drawImage(mapImage, 0, 0, sampleW, sampleH);
            const data = offCtx.getImageData(0, 0, sampleW, sampleH).data;

            points = [];

            // Scan pixels
            for (let y = 0; y < sampleH; y++) {
                for (let x = 0; x < sampleW; x++) {
                    const i = (y * sampleW + x) * 4;
                    const r = data[i];
                    const g = data[i + 1];
                    const b = data[i + 2];

                    // Simple brightness check (white is continent)
                    const brightness = (r + g + b) / 3;

                    if (brightness > 100) { // Threshold
                        // Map x,y to Lat,Lon
                        // y=0 -> Lat 90, y=H -> Lat -90
                        const lat = 90 - (y / sampleH) * 180;
                        // x=0 -> Lon -180, x=W -> Lon 180
                        // Since our getSpherePoint expects lon+180 offset in theta, 
                        // let's pass standard lon (-180 to 180).
                        const lon = (x / sampleW) * 360 - 180;

                        // Add small jitter
                        const safeLat = lat + (Math.random() - 0.5) * 1.5;
                        const safeLon = lon + (Math.random() - 0.5) * 1.5;

                        // Reject extreme poles to avoid clustering
                        if (Math.abs(safeLat) > 85) continue;

                        // Randomly skip some dots for "tech" feel
                        if (Math.random() > 0.7) continue;

                        const p = getSpherePoint(safeLat, safeLon, config.radius);
                        points.push({
                            ...p,
                            isHub: false
                        });
                    }
                }
            }

            // Add Hubs
            hubs.forEach(hub => {
                const p = getSpherePoint(hub.lat, hub.lon, config.radius);
                points.push({
                    ...p,
                    isHub: true,
                    name: hub.name,
                    ...hub
                });
                hub.p3d = p;
            });

            // Start Animation
            loader.style.display = 'none';
            canvas.classList.remove('opacity-0');
            animate();
        }

        function project(x, y, z, rotationAngle) {
            // Rotate around Y axis
            // Offset rotation by -Math.PI/2 to center Greenwich/Europe roughly at start if rot=0
            // But we increment rotation.
            const angle = rotationAngle - 1.5; // Initial offset 

            const cos = Math.cos(angle);
            const sin = Math.sin(angle);

            const rotX = x * cos - z * sin;
            const rotZ = x * sin + z * cos;

            // Perspective
            const scale = 900 / (900 - rotZ);
            const px = rotX * scale + config.width / 2;
            const py = y * scale + config.height / 2;

            return {
                x: px,
                y: py,
                z: rotZ,
                scale,
                visible: rotZ < 0
            };
        }

        function createBeam() {
            // Pick two visible hubs
            const visibleHubs = hubs.filter(h => {
                const p = project(h.p3d.x, h.p3d.y, h.p3d.z, rotation);
                return p.visible; // Front facing
            });

            if (visibleHubs.length < 2) return;

            const startIdx = Math.floor(Math.random() * visibleHubs.length);
            let endIdx = Math.floor(Math.random() * visibleHubs.length);
            while (startIdx === endIdx) {
                endIdx = Math.floor(Math.random() * visibleHubs.length);
            }

            beams.push({
                start: visibleHubs[startIdx],
                end: visibleHubs[endIdx],
                progress: 0,
                speed: 0.015 + Math.random() * 0.02,
                arcHeight: 50 + Math.random() * 30
            });
        }

        function animate() {
            ctx.clearRect(0, 0, config.width, config.height);
            rotation += config.rotationSpeed;

            // Sort points by Z for depth (simple painter's algo approximation mainly for hubs vs bg)
            // But for simple dots, simple order is fine. Depth sorting is expensive for 2000 dots in JS.
            // We optimized by just checking visibility threshold.

            // 1. Draw Dots
            points.forEach(p => {
                const proj = project(p.x, p.y, p.z, rotation);

                // Opacity based on Z (depth)
                // Front (rotZ < 0) is visible. Back is dimmed or hidden.
                // Let's hide back half for cleaner map look or dim it heavily.

                if (!proj.visible && !p.isHub) return; // Hide back dots

                let alpha = 1;
                if (!proj.visible) alpha = 0.1; // Dim back hubs
                else alpha = mapRange(proj.z, -config.radius, 0, 1, 0.4); // Fade as it curves away

                const color = p.isHub ? config.colors.activeNode : config.colors.dot;
                const size = p.isHub ? 5 * proj.scale : config.dotSize * proj.scale;

                ctx.beginPath();
                ctx.arc(proj.x, proj.y, size, 0, Math.PI * 2);
                ctx.fillStyle = p.isHub ? color : `rgba(255,255,255,${alpha * 0.5})`;
                ctx.fill();

                if (p.isHub && proj.visible) {
                    // Glow
                    ctx.shadowBlur = 10;
                    ctx.shadowColor = config.colors.activeNode;
                    ctx.fill();
                    ctx.shadowBlur = 0;
                }
            });

            // 2. Beams
            if (Math.random() < 0.04) createBeam();

            for (let i = beams.length - 1; i >= 0; i--) {
                const beam = beams[i];
                beam.progress += beam.speed;
                if (beam.progress >= 1) {
                    beams.splice(i, 1);
                    continue;
                }

                const startP = project(beam.start.p3d.x, beam.start.p3d.y, beam.start.p3d.z, rotation);
                const endP = project(beam.end.p3d.x, beam.end.p3d.y, beam.end.p3d.z, rotation);

                // Draw curve
                const midX = (startP.x + endP.x) / 2;
                const midY = (startP.y + endP.y) / 2;
                const ctrlX = midX;
                const ctrlY = midY - beam.arcHeight * startP.scale;

                // Bezier Interpolation
                const t = beam.progress;
                const invT = 1 - t;
                const curX = (invT * invT * startP.x) + (2 * invT * t * ctrlX) + (t * t * endP.x);
                const curY = (invT * invT * startP.y) + (2 * invT * t * ctrlY) + (t * t * endP.y);

                // Draw Tail
                ctx.beginPath();
                ctx.moveTo(startP.x, startP.y);
                ctx.quadraticCurveTo(ctrlX, ctrlY, curX, curY);
                ctx.strokeStyle = `rgba(124, 58, 237, ${1 - t})`;
                ctx.lineWidth = 2;
                ctx.stroke();

                // Draw Head
                ctx.beginPath();
                ctx.arc(curX, curY, 3, 0, Math.PI * 2);
                ctx.fillStyle = config.colors.beamHead;
                ctx.fill();
            }

            requestAnimationFrame(animate);
        }

        // Utils
        function mapRange(value, inMin, inMax, outMin, outMax) {
            return (value - inMin) * (outMax - outMin) / (inMax - inMin) + outMin;
        }

        // Load Map Image
        mapImage.crossOrigin = "Anonymous"; // In case it's external, but we use local
        mapImage.src = "{{ asset('assets/images/world_map_mask.png') }}";
        mapImage.onload = processMapData;

        // Fallback or Error
        mapImage.onerror = function() {
            console.error("Map image failed to load");
            // Fallback to random dots?
            // initRandomPoints(); 
        };

    })();
</script>
