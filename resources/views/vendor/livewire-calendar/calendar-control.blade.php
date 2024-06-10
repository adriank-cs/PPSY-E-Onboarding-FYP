@use('Carbon\Carbon')

<div class="grid grid-rows-1 grid-cols-3 gap-x-3 grid-flow-col py-2 min-w-full ">

    <div class="justify-self-start">
        <div class="flex flex-row gap-3">
            <button wire:click="previousMonth" class="btn btn-outline-primary"><i class="ti ti-chevron-left"></i></button>
            <button wire:click="currentMonth" class="btn btn-primary">Current</button>
        </div>

    </div>

    <div class="justify-self-center">
    <h1 class="text-primary">{{$startsAt->englishMonth}} {{$startsAt->year}}</h1>
    </div>

    <div class="justify-self-end">
        <button wire:click="nextMonth" class="btn btn-outline-primary"><i class="ti ti-chevron-right"></i></button>
    </div>
    
</div>