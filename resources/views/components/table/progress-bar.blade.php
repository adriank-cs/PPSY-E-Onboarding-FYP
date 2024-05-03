<div {{ $attributes }}>
    <div class="progress" style="width:12rem; height: 2em">
        <div class="progress-bar" role="progressbar" style="width: {{ $attributes->get('progress') }}" aria-valuenow="{{ $attributes->get('progress') }}" aria-valuemin="0" aria-valuemax="100">{{ $attributes->get('progress') }}</div>
    </div>
</div>