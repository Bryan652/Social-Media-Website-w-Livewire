<?php

use Livewire\Volt\Component;

new class extends Component {
    public $user;
    public $friends;
    public function mount()
    {
        $this->user = auth()->user(); // or however you get the user
        $this->friends = auth()->user()->allFriends();
    }
}; ?>

<div>
    @foreach ($friends as $friend)
        <x-mary-avatar :image="$friend->profile_picture" :title="$friend->username" :subtitle="$friend->name" class="!w-10 m-2" />
    @endforeach
</div>
