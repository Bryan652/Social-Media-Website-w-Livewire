<?php

use Livewire\Volt\Component;
use App\Models\user;

new class extends Component {
    public $user;
    public $friends;
    public $notFriends;

    public function mount()
    {
        $this->user = auth()->user();
        $this->friends = $this->user->allFriends();
        $friendIds = $this->friends->pluck('id')->push($this->user->id);
        $this->notFriends = User::whereNotIn('id', $friendIds)->get();
    }

    public function addFriend($friendId)
    {
        $this->user->addFriend($friendId); // implement this in your User model
        $this->friends = $this->user->allFriends();
        $friendIds = $this->friends->pluck('id')->push($this->user->id);
        $this->notFriends = User::whereNotIn('id', $friendIds)->get();
    }
}; ?>
<div>



    <h2 class="font-bold">Your Following</h2>
    @foreach ($friends as $friend)
        <x-mary-avatar :image="$friend->profile_picture" :title="$friend->username" :subtitle="$friend->name" class="!w-10 m-2" />
    @endforeach

    <h2 class="font-bold mt-2">Add Following</h2>
    @foreach ($notFriends as $nf)
        <div class="flex items-center space-x-2 mb-2">
            <x-mary-avatar :image="$nf->profile_picture" :title="$nf->username" :subtitle="$nf->name" class="!w-10 m-2" />
            <x-mary-button wire:click="addFriend({{ $nf->id }})" class="btn-primary">Add Friend</x-mary-button>
        </div>
    @endforeach
</div>
