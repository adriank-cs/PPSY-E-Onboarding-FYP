<div
    @if($eventClickEnabled)
        wire:click.stop="onEventClick('{{ $event['id']  }}')"
    @endif
    class="tw-bg-white tw-rounded-lg tw-border tw-py-2 tw-px-2 tw-shadow-md tw-cursor-pointer">

    <p class="tw-text-sm tw-font-medium">
        {{ $event['title'] }}
    </p>
    <p class="tw-mt-2 tw-text-xs">
        {{ $event['description'] ?? 'No description' }}
    </p>
</div>
