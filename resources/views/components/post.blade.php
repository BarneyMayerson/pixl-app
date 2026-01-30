<li class="not-first:pt-2.5 flex items-start gap-4">
  <a href="{{ route('profiles.show', $post->profile) }}" class="shrink-0">
    <img src="{{ $post->profile->avatar_url }}" alt="Avatar for {{ $post->profile->display_name }}"
      class="size-10 object-cover" />
  </a>
  <div class="grow pt-1.5">
    <div class="border-pixl-light/10 border-b pb-5">
      <!-- User meta -->
      <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-2.5">
          <p><a class="hover:underline"
              href="{{ route('profiles.show', $post->profile) }}">{{ $post->profile->display_name }}</a></p>
          <p class="text-pixl-light/40 text-xs">
            <a href="{{ route('posts.show', [$post->profile, $post]) }}">{{ $post->created_at }}</a>
          </p>
          <p>
            <a class="text-pixl-light/40 hover:text-pixl-light/60 text-xs"
              href="{{ route('profiles.show', $post->profile) }}">{{ $post->profile->handle }}</a>
          </p>
        </div>
        <button class="gap-0.75 group flex py-2" aria-label="Post options">
          <span class="bg-pixl-light/40 group-hover:bg-pixl-light/60 size-1"></span>
          <span class="bg-pixl-light/40 group-hover:bg-pixl-light/60 size-1"></span>
          <span class="bg-pixl-light/40 group-hover:bg-pixl-light/60 size-1"></span>
        </button>
      </div>
      <!-- Post content -->
      <div class="[&_a]:text-pixl mt-4 flex flex-col gap-3 text-sm [&_a]:hover:underline">
        <p>
          {!! $post->content !!}

          @if ($post->isRepost() && $post->content != null)
            <ul>
              <x-post :post="$post->repostOf" :show-engagement="false" />
            </ul>
          @endif
        </p>
      </div>

      @if ($showEngagement)
        <!-- Action buttons -->
        <div class="mt-6 flex items-center justify-between gap-4">

          <div class="flex items-center gap-8">
            <x-like-button :active="$post->liked_by_viewer" :count="$post->likes_count" :id="$post->id" />
            <x-reply-button :count="$post->replies_count" :id="$post->id" />
            <x-repost-button :active="$post->reposted_by_viewer" :count="$post->reposts_count" :id="$post->id" />
          </div>

          <div class="flex items-center gap-3">
            <x-save-button :id="$post->id" />
            <x-share-button :id="$post->id" />
          </div>
        </div>
      @endif

      <x-reply-form :post="$post" />

    </div>

    @if ($showReplies)
      <ol>
        @foreach ($post->replies as $reply)
          <x-reply :post="$reply" :show-engagement="$showEngagement" :show-replies="$showReplies" />
        @endforeach
      </ol>
    @endif
  </div>
</li>
