<div
    @if($pollMillis !== null && $pollAction !== null)
        wire:poll.{{ $pollMillis }}ms="{{ $pollAction }}"
    @elseif($pollMillis !== null)
        wire:poll.{{ $pollMillis }}ms
    @endif
>
    <div>
        @includeIf($beforeCalendarView)
    </div>

    <div class="tw-flex">
        <div class="tw-overflow-x-auto tw-w-full">
            <div class="tw-inline-block tw-min-w-full tw-overflow-hidden">

                <div class="tw-w-full tw-flex tw-flex-row">
                    @foreach($monthGrid->first() as $day)
                        @include($dayOfWeekView, ['day' => $day])
                    @endforeach
                </div>

                @foreach($monthGrid as $week)
                    <div class="tw-w-full tw-flex tw-flex-row">
                        @foreach($week as $day)
                            @include($dayView, [
                                    'componentId' => $componentId,
                                    'day' => $day,
                                    'dayInMonth' => $day->isSameMonth($startsAt),
                                    'isToday' => $day->isToday(),
                                    'events' => $getEventsForDay($day, $events),
                                ])
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div>
        @includeIf($afterCalendarView)
    </div>
</div>
