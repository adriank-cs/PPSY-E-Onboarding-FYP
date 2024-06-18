@use('Carbon\Carbon')

<div class="tw-grid tw-grid-rows-1 tw-grid-cols-6 tw-py-2 tw-min-w-full ">

    <div class="tw-col-span-3">
            <button wire:click="previousMonth" class="btn btn-outline-primary"><i class="ti ti-chevron-left"></i></button>
            <button wire:click="currentMonth" class="btn btn-primary">Current</button>
    </div>

    <div class="tw-col-span-2">
        <h1 class="text-primary">{{$startsAt->englishMonth}} {{$startsAt->year}}</h1>
    </div>

    <div class="tw-col-span-1 tw-justify-self-end">
        <button wire:click="nextMonth" class="btn btn-outline-primary"><i class="ti ti-chevron-right"></i></button>
    </div>
    
</div>