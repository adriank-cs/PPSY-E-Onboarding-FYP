
<div
    ondragenter="onLivewireCalendarEventDragEnter(event, '{{ $componentId }}', '{{ $day }}', '{{ $dragAndDropClasses }}');"
    ondragleave="onLivewireCalendarEventDragLeave(event, '{{ $componentId }}', '{{ $day }}', '{{ $dragAndDropClasses }}');"
    ondragover="onLivewireCalendarEventDragOver(event);"
    ondrop="onLivewireCalendarEventDrop(event, '{{ $componentId }}', '{{ $day }}', {{ $day->year }}, {{ $day->month }}, {{ $day->day }}, '{{ $dragAndDropClasses }}');"
    class="tw-flex-1 tw-h-28 lg:tw-h-32 border tw-border-gray-200 -tw-mt-px -tw-ml-px"
    style="min-width: 10rem;">

    {{-- Wrapper for Drag and Drop --}}
    <div
        class="tw-w-full tw-h-full"
        id="{{ $componentId }}-{{ $day }}">

        <div
            @if($dayClickEnabled)
                wire:click="onDayClick({{ $day->year }}, {{ $day->month }}, {{ $day->day }})"
            @endif
            class="tw-w-full tw-h-full tw-p-1 {{ $dayInMonth ? $isToday ? 'tw-bg-yellow-100' : ' tw-bg-white ' : 'tw-bg-gray-100' }} tw-flex tw-flex-col">

            {{-- Number of Day --}}
            <div class="tw-flex tw-items-center">
                <p class="tw-text-sm {{ $dayInMonth ? ' tw-font-medium ' : '' }}">
                    {{ $day->format('j') }}
                </p>
                <p class="tw-text-xs tw-text-gray-600 tw-ml-4">
                    @if($events->isNotEmpty())
                        {{ $events->count() }} {{ Str::plural('event', $events->count()) }}
                    @endif
                </p>
            </div>

            {{-- Events --}}
            <div class="tw-p-1 tw-my-1 tw-flex-auto tw-overflow-y-auto">
                <div class="tw-grid tw-grid-cols-1 tw-grid-flow-row tw-gap-2">
                    @foreach($events as $event)
                        <div
                            @if($dragAndDropEnabled)
                                draggable="true"
                            @endif
                            ondragstart="onLivewireCalendarEventDragStart(event, '{{ $event['id'] }}')">
                            @include($eventView, [
                                'event' => $event,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
