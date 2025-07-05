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

    public function refreshArticles() {
        $this->userArticle = auth()->user()->article()->with('user')->latest()->get();
        $this->article = article::where('user_id', '!=', auth()->id())->with('user')->latest()->get();
    }

    public function delete($id) {
        $article = article::findOrFail($id);
        if ($article->user_id == auth()->id()) {
            $article->delete();
            $this->refreshArticles();
        }
    }

    public function updateArticle($id, $newText) {
        $article = article::findOrFail($id);
        if ($article->user_id == auth()->id()) {
            $article->postText = $newText;
            $article->save();
            $this->refreshArticles();
        }
    }
}; ?>

<div>
    <h1 class="mb-8"> User Posts</h1>
    <div class="rounded-md">
        @foreach ($userArticle as $item)
            <div class="mb-8 bg-slate-600 rounded-lg shadow p-4 border border-transparent hover:border-blue-500">
                <div class="flex items-center mb-3">
                    <x-mary-avatar :image="$item->user->profile_picture" :title="$item->user->name" class="!w-12 !h-12" />
                </div>
                <div x-data="{ edit: false, newText: '{{ addslashes($item->postText) }}' }">
                    <div x-show="!edit">
                        <div class="text-white mb-3" x-text="newText"></div>
                        @if ($item->user_id == auth()->id())
                            <x-mary-button @click="edit = true" class="btn-primary">Edit</x-mary-button>
                        @endif
                    </div>
                    <div x-show="edit" class="flex items-center space-x-2">
                        <input type="text" x-model="newText" class="rounded p-2 w-full bg-gray-700 text-white" />

                        <x-mary-button
                            @click="
                                $wire.call('updateArticle', {{ $item->id }}, newText);
                                edit = false;
                            "
                            class="btn-success"
                        >Save</x-mary-button>

                        <x-mary-button wire:click="delete({{ $item->id }})" onclick="return confirm('Are you sure?')" class="btn-secondary ">
                                Delete
                        </x-mary-button>

                        <x-mary-button @click="newText = '{{ addslashes($item->postText) }}'; edit = false" class="btn-secondary">Cancel</x-mary-button>
                    </div>
                </div>

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
                                x-ref="img"
                                src="{{ Str::startsWith($item->postImage, ['http://', 'https://']) ? $item->postImage : asset('storage/' . $item->postImage) }}"
                                alt=""
                                class="rounded-lg max-h-96 w-full object-cover"
                                @load="loaded = true"
                                x-show="loaded"
                                x-init="if ($refs.img.complete) loaded = true"
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
                                x-ref="img"
                                src="{{ Str::startsWith($item->postImage, ['http://', 'https://']) ? $item->postImage : asset('storage/' . $item->postImage) }}"
                                alt=""
                                class="rounded-lg max-h-96 w-full object-cover"
                                @load="loaded = true"
                                x-show="loaded"
                                x-init="if ($refs.img.complete) loaded = true"
                            >
                        </div>
                    </div>
                @endif
                <x-postButtons />
            </div>
        @endforeach
    </div>
</div>
