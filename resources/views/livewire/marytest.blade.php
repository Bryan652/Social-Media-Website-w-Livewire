<?php

use Livewire\Volt\Component;

new class extends Component {

}; ?>

<div>
    @php
        $slides = [
            ['image' => '/photos/photo-1494253109108-2e30c049369b.jpg'],
            ['image' => '/photos/photo-1565098772267-60af42b81ef2.jpg'],
            ['image' => '/photos/photo-1559703248-dcaaec9fab78.jpg'],
            ['image' => '/photos/photo-1572635148818-ef6fd45eb394.jpg'],
        ];
    @endphp

    <x-mary-carousel :slides="$slides" />
</div>
