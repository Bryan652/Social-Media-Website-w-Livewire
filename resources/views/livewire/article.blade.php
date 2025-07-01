<?php

use Livewire\Volt\Component;
use App\Models\article;

new class extends Component {
    public $article;
    public $userArticle;

    public function mount() {
        $this->userArticle = auth()->user()->article()->with('user')->latest()->get();
        /*
            for lazy loading prevention
            kapag tumawag ka ng ibang table ex. article tapos isasabay mo yung user
            userArticle = auth()->user()->article()->latest()->get();
            nakuwa nga yung data pero per kuwa ng data bagong open ng query sa user
            userArticle = auth()->user()->article()->with('user')->latest()->get();
            pag ganto isasabay na yung pagkuwa ng data nung user and article
        */
        $this->article = article::where('user_id', '!=', auth()->id())->with('user')->latest()->get();
    }
}; ?>

<div>

    <h1 class="mb-8"> User Posts</h1>
    <div class="rounded-md">
        @foreach ($userArticle as $item)
            <div class="mb-8 bg-slate-600 rounded-lg shadow p-4 border border-transparent hover:border-blue-500">
                <div class="flex items-center mb-3">
                    <x-mary-avatar :image="$item->user->profile_picture" :title="$item->user->name" class="!w-12 !h-12" />
                        @if ($item->user_id == auth()->id())
                            <a class="ml-auto" href="{{ route('profile', $item->id) }}">
                                <x-mary-button label="Edit" class="btn-primary" />
                            </a>
                        @endif

                </div>
                <div class="text-white mb-3">{{ $item->postText }}</div>

                <!-- Check kung meron image -->
                @if($item->postImage)
                    <div class="mb-3 relative">
                        <!-- gagawa yung alpinejs ng local state loaded initial value = false-->
                        <div x-data="{ loaded: false }">
                            <!-- pag di pa nagloload papakita tong block -->
                            <template x-if="!loaded">
                                <x-mary-loading class="inset-0 z-10" />
                            </template>
                            <!-- Hidden to hanggang matapos mag load yung pic-->
                            <img
                                src="{{ $item->postImage }}"
                                alt=""
                                class="rounded-lg max-h-96 w-full object-cover"
                                @load="loaded = true"
                            >
                        </div>
                    </div>
                @endif
                <x-postButtons />
            </div>
        @endforeach
    </div>

    <h1 class="mb-8"> Others Posts</h1>
    <div class="rounded-md">
        @foreach ($article as $item)
            <div class="mb-8 bg-slate-600 rounded-lg shadow p-4 border border-transparent hover:border-blue-500">
                <div class="flex items-center mb-3">
                    <x-mary-avatar :image="$item->user->profile_picture" :title="$item->user->name" class="!w-12 !h-12" />
                </div>
                <div class="text-white mb-3">{{ $item->postText }}</div>

                <!-- Check kung meron image -->
                @if($item->postImage)
                    <div class="mb-3 relative">
                        <!-- gagawa yung alpinejs ng local state loaded initial value = false-->
                        <div x-data="{ loaded: false }">
                            <!-- pag di pa nagloload papakita tong block -->
                            <template x-if="!loaded">
                                <x-mary-loading class="inset-0 z-10" />
                            </template>
                            <!-- Hidden to hanggang matapos mag load yung pic-->
                            <img
                                src="{{ $item->postImage }}"
                                alt=""
                                class="rounded-lg max-h-96 w-full object-cover"
                                @load="loaded = true"
                            >
                        </div>
                    </div>
                @endif
                <x-postButtons />
            </div>
        @endforeach
    </div>
</div>
