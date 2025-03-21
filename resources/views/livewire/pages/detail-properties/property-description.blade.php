<?php

use Livewire\Volt\Component;
use App\Models\Properties;

new class extends Component {
    public Properties $property;
}; ?>

<div>
    <div class="property-description">
        {!! nl2br(e($property->description)) !!}
    </div>
</div>
