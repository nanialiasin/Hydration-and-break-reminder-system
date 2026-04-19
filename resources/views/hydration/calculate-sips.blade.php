@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/calculate-sips.css') }}">
@endsection

@section('content')

<div class="min-h-screen bg-gray-300 flex justify-center">

    <!-- Phone Container -->
    <div class="w-full max-w-md bg-gray-200 relative">

        <!-- Scroll Area -->
        <div class="px-4 pb-24 overflow-y-auto h-[85vh]">

            <!-- Main Card -->
            <div class="bg-gray-100 rounded-3xl p-6">

                <!-- Title -->
                <h2 class="text-xl font-semibold mb-5">
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

                    <!-- <input
                        type="number"
                        step="0.001"
                        name="empty_weight"
                        class="w-full border-b-2
                               border-gray-400
                               bg-transparent
                               focus:outline-none
                               mt-2"> -->

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

                    <!-- <input
                        type="number"
                        step="0.001"
                        name="filled_weight"
                        class="w-full border-b-2
                               border-gray-400
                               bg-transparent
                               focus:outline-none
                               mt-2"> -->

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
                        = Total water (kg)
                    </p>

                    <p class="text-gray-700 mt-3 ">
                        Since 1 kg of water = 1000 mL, convert:
                    </p>

                    <p class="text-gray-700 mt-3 ">
                        Total water (kg) x 1000 = Total water (mL) ( Result C )
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

                    <p class="mt-3 text-gray-700">
                        Calculate water used:
                    </p>

                    <p class="mt-3 text-gray-700">
                        Initial water weight ( Result C ) - Remaining water weight ( Result D ) = Water consumed (kg) ( Result E )
                    </p>

                    <p class="mt-3 text-gray-700">
                        Convert to mL:
                        Water consumed (kg) ( Result E ) x 1000 = Total water consumed (mL) ( Result F )
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
                        Average mL per sip ( Final Result ) = Total water consumed (mL) ( Result F ) ÷ Number of sips
                    </p>

                </div>

                <div class="step-divider"></div>

                <div>
                    <h4 class="text-600 font-semibold">
                        You can reread this prompt later, it is located in the "To-Do Session" page.
                    </h4>
                </div>

            </div>

        </div>

        <!-- Bottom Continue Button -->
        <div class="bottom-center-btn">
            <form method="POST" action="{{ route('calculate.sips.finish') }}">
                @csrf
                <button type="submit" class="bg-black text-white px-8 py-3 rounded-xl shadow-md hover:bg-gray-800 transition">
                    Continue
                </button>
            </form>
        </div>

    </div>

</div>

@endsection