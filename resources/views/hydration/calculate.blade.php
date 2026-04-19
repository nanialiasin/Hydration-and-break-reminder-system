@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/calculate-sips.css') }}">
@endsection

@section('content')

<div class="min-h-screen bg-gray-300 flex justify-center">

    <!-- Phone Container -->
    <div class="w-full max-w-md bg-gray-200 relative">

        <!-- Scroll Area -->
        <form method="POST" action="{{ route('calculate.sips.finish') }}">
        @csrf

        <div class="px-4 pb-24 overflow-y-auto h-[85vh]">

            <!-- Main Card -->
            <div class="bg-gray-100 rounded-3xl p-6">

                <!-- Title -->
                <h2 class="text-xl font-semibold mb-5">
                    <a href="{{ route('training') }}" class="back-btn" style="text-decoration: none;">←</a>
                    How To Calculate Average Sip
                </h2>

                <div>
                    <p class="text-gray-700">
                        Best to do this before your training session to get an accurate estimate of how much water you consume with each sip.
                    </p>

                    <p class="text-gray-700">
                        Follow these steps to determine the average mL per sip:
                    </p>
        
                </div>

                <!-- STEP 1 -->
                <div class="mb-6">

                    <h3 class="text-blue-600 font-semibold">
                        Step 1: Weigh Empty Bottle
                    </h3>

                    <p class="text-gray-700 mt-2">
                        Users weigh their empty water bottle using
                        a digital scale and record the weight.
                    </p>

                    <p class="mt-3 text-gray-700">
                        Empty bottle weight =
                        ___ kg ( Result A )
                    </p>

                    <input
                        id="empty_weight"
                        type="number"
                        step="0.001"
                        name="empty_weight"
                        value="{{ old('empty_weight', session('draft_empty_weight')) }}"
                        class="w-full border-b-2
                               border-gray-400
                               bg-transparent
                               focus:outline-none
                               mt-2">

                </div>

                <div class="step-divider"></div>

                <!-- STEP 2 -->

                <div class="mb-6">

                    <h3 class="text-blue-600 font-semibold">
                        Step 2: Fill the Bottle
                    </h3>

                    <p class="text-gray-700 mt-2">
                        Fill the bottle with any amount of water.
                    </p>

                </div>

                <div class="step-divider"></div>

                <!-- STEP 3 -->

                <div class="mb-6">

                    <h3 class="text-blue-600 font-semibold">
                        Step 3: Weigh Filled Bottle
                    </h3>

                    <p class="text-gray-700 mt-2">
                        Weigh the filled bottle and record the weight.
                    </p>

                    <p class="mt-3 text-gray-700">
                        Filled bottle weight =
                        ___ kg ( Result B )
                    </p>

                    <input
                        id="filled_weight"
                        type="number"
                        step="0.001"
                        name="filled_weight"
                        value="{{ old('filled_weight', session('draft_filled_weight')) }}"
                        class="w-full border-b-2
                               border-gray-400
                               bg-transparent
                               focus:outline-none
                               mt-2">

                </div>

                <div class="step-divider"></div>

                <!-- STEP 4 -->

                <div class="mb-6">

                    <h3 class="text-blue-600 font-semibold">
                        Step 4: Calculate Initial Water Amount
                    </h3>

                    <p class="text-gray-700 mt-2">
                        Subtract empty bottle weight from
                        filled bottle weight.
                    </p>

                    <p class="text-gray-700 mt-3 ">
                        Filled bottle weight ( Result B )
                        − Empty bottle weight ( Result A )
                        = <span id="total_water_kg">0</span> kg
                    </p>

                    <p class="text-gray-700 mt-3 ">
                        Since 1 kg of water = 1000 mL, convert:
                    </p>

                    <p class="text-gray-700 mt-3 ">
                        Total water (kg) x 1000 = <span id="total_water_ml">0</span> mL (Result C)
                    </p>

                </div>

                <div class="step-divider"></div>

                <!-- STEP 5 -->
                <div class="mb-6">

                    <h3 class="text-blue-600 font-semibold">
                        Step 5: Count The mL For Each Sip
                    </h3>

                    <p class="text-gray-700 mt-2">
                        During the training session:
                    </p>

                    <p class="mt-3 text-gray-700">
                        Each time the user takes a sip, they count it.
                    </p>

                    <p class="mt-3 text-gray-700">
                        Example: 
                        Sip count = 1, 2, 3…
                    </p>

                    <input
                        id="sip_count"
                        type="number"
                        step="0.001"
                        name="sip_count"
                        value="{{ old('sip_count', session('draft_sip_count')) }}"
                        class="w-full border-b-2
                               border-gray-400
                               bg-transparent
                               focus:outline-none
                               mt-2">

                </div>

                <div class="step-divider"></div>

                <!-- STEP 6 -->
                <div class="mb-6">

                    <h3 class="text-blue-600 font-semibold">
                        Step 6: Weigh Bottle After Drinking
                    </h3>

                    <p class="text-gray-700 mt-2">
                        After finishing the session: 
                        Weigh the bottle again.
                    </p>

                    <p class="mt-3 text-gray-700">
                        Remaining bottle weight = ___ kg ( Result D )
                    </p>

                    <input
                        id="remaining_weight"
                        type="number"
                        step="0.001"
                        name="remaining_weight"
                        value="{{ old('remaining_weight', session('draft_remaining_weight')) }}"
                        class="w-full border-b-2
                               border-gray-400
                               bg-transparent
                               focus:outline-none
                               mt-2">

                    <p class="mt-3 text-gray-700">
                        Calculate water used:
                    </p>

                    <p class="mt-3 text-gray-700">
                        Initial water weight ( Result C ) - Remaining water weight ( Result D ) = <span id="water_consumed_kg">0</span> kg ( Result E )
                    </p>

                    <p class="mt-3 text-gray-700">
                        Convert to mL:
                    </p>

                    <p class="mt-3 text-gray-700">
                        Water consumed (kg) ( Result E ) x 1000 = <span id="water_consumed_ml">0</span> mL ( Result F )
                    </p>

                </div>
                
                <div class="step-divider"></div>

                <!-- STEP 7 -->
                <div class="mb-6">

                    <h3 class="text-blue-600 font-semibold">
                        Step 7: Calculate Average mL Per Sip
                    </h3>

                    <p class="text-gray-700 mt-2">
                        Divide total water consumed by total number of sips.
                    </p>

                    <p class="mt-3 text-gray-700">
                        Total water consumed (mL) ( Result F ) ÷ Number of sips = <span id="avg_ml_per_sip">0</span> mL per sip ( Final Result )
                    </p>

                </div>

            </div>

        </div>

        <div class="bottom-center-btn">
            <button id="saveDraftBtn" type="button" class="bg-green-600 text-white px-8 py-3 rounded-xl shadow-md hover:bg-green-700 transition">
                Save Changes
            </button>
            <button id="submitBtn" type="submit" class="bg-green-600 text-white px-8 py-3 rounded-xl shadow-md hover:bg-green-700 transition" style="display: none;">
                Submit
            </button>
        </div>

        </form>

    </div>

</div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Inputs for validation and live calculation ---
    const emptyWeightInput = document.getElementById('empty_weight');
    const filledWeightInput = document.getElementById('filled_weight');
    const sipCountInput = document.getElementById('sip_count');
    const remainingWeightInput = document.getElementById('remaining_weight');

    const inputs = [emptyWeightInput, filledWeightInput, sipCountInput, remainingWeightInput];

    // --- Buttons ---
    const saveDraftBtn = document.getElementById('saveDraftBtn');
    const submitBtn = document.getElementById('submitBtn');
    const form = saveDraftBtn.closest('form');

    // --- Live calculation spans ---
    const totalWaterKgSpan = document.getElementById('total_water_kg');
    const totalWaterMlSpan = document.getElementById('total_water_ml');
    const waterConsumedKgSpan = document.getElementById('water_consumed_kg');
    const waterConsumedMlSpan = document.getElementById('water_consumed_ml');
    const avgMlPerSipSpan = document.getElementById('avg_ml_per_sip');

    // --- Show/hide buttons based on input completeness ---
    function checkInputs() {
        let allFilled = inputs.every(input => input.value && input.value.trim() !== "");
        if (allFilled) {
            saveDraftBtn.style.display = "none";
            submitBtn.style.display = "inline-block";
        } else {
            saveDraftBtn.style.display = "inline-block";
            submitBtn.style.display = "none";
        }
    }
    
    function updateStep6Results() {
        const empty = parseFloat(emptyWeightInput.value) || 0;
        const filled = parseFloat(filledWeightInput.value) || 0;
        const remaining = parseFloat(remainingWeightInput.value) || 0;
        const sipCount = parseFloat(sipCountInput.value) || 0;

        const totalKg = filled - empty; // Result C
        const consumedKg = totalKg - (remaining - empty); // Result E
        const consumedMl = consumedKg * 1000; // Result F

        waterConsumedKgSpan.textContent = consumedKg > 0 ? consumedKg.toFixed(3) : '0';
        waterConsumedMlSpan.textContent = consumedKg > 0 ? consumedMl.toFixed(0) : '0';

        // Step 7: Average mL per sip
        let avgMlPerSip = (sipCount > 0 && consumedMl > 0) ? (consumedMl / sipCount) : 0;
        avgMlPerSipSpan.textContent = avgMlPerSip > 0 ? avgMlPerSip.toFixed(1) : '0';

    }

    // --- Live calculation for Step 4 ---
    function updateResults() {
        const empty = parseFloat(emptyWeightInput.value) || 0;
        const filled = parseFloat(filledWeightInput.value) || 0;
        const totalKg = filled - empty;
        const totalMl = totalKg * 1000;

        totalWaterKgSpan.textContent = totalKg > 0 ? totalKg.toFixed(3) : '0';
        totalWaterMlSpan.textContent = totalKg > 0 ? totalMl.toFixed(0) : '0';
    
        updateStep6Results();
    }

    // --- Initial checks ---
    checkInputs();
    updateResults();

    // --- Event listeners ---
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            checkInputs();
            updateResults();
        });
    });

    // --- Save draft logic ---
    saveDraftBtn.addEventListener('click', function() {
        let draftInput = document.createElement('input');
        draftInput.type = 'hidden';
        draftInput.name = 'save_draft';
        draftInput.value = '1';
        form.appendChild(draftInput);
        form.submit();
    });
});
</script>