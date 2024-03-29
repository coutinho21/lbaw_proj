<li>
    <div id="{{ $comment['id'] }}" class="comment">
        <div class="comment-header">
            <div class="comment-header-title-likes">
                @if(isset($comment->user->username)) 
                    @if($comment->user->id == $event->id_owner)
                        <div class="event-owner-message">
                            <h4>{{ $comment->user->username }}</h4>
                            <p class="event-owner">Event Owner Message</p>
                        </div>
                    @else
                    <h4>{{ $comment->user->username }}</h4>
                    @endif
                @else
                    <h4>Anonymous</h4>  
                @endif
                @if (Auth::check() && (Auth::user()->events->contains($comment->id_event) || Auth::user()->id == $event->id_owner))
                    <div class="likes-dislikes">
                        <?php
                            $likeClass = "comment-like";
                            $dislikeClass = "comment-dislike";
                            $likeSrc = url('icons/like.png');
                            $dislikeSrc = url('icons/like.png');
                            $likeDislikeUser = $comment->likesDislikes->where('id_user', Auth::user()->id)->first();
                            if( $likeDislikeUser !== null){
                                if($likeDislikeUser->liked){
                                    $likeSrc = url('icons/blue_like.png');
                                    $likeClass = $likeClass . " comment-like-active";
                                }
                                else {
                                    $dislikeSrc = url('icons/blue_like.png');
                                    $dislikeClass = $dislikeClass . " comment-dislike-active";
                                }
                            }
                        ?>
                        <p class="comment-like-number">{{ $comment->likes }}</p>
                        <img id="{{ Auth::user()->id }}" class="{{ $likeClass }}" src="{{ $likeSrc }}" alt="like">
                        <p class="comment-dislike-number">{{ $comment->dislikes }}</p>
                        <img id="{{ Auth::user()->id }}" class="{{ $dislikeClass }}" src="{{ $dislikeSrc }}" alt="dislike">
                    </div>
                @endif
            </div>
            @if (Auth::check() && !Auth::user()->blocked && (Auth::user()->id === $comment->id_user || Auth::user()->admin))
                <div class="comment-actions">
                    <button title="Edit Comment" class="fake button edit-comment no-button" id="{{ $comment->id }}">
                        &#9998;
                    </button>
                    <button title="Remove Comment" class="fake button delete-comment no-button" id="{{ $comment->id }}">
                        &#128465;
                    </button>
                </div>
            @endif
        </div>
        <p class="comment-text">{{ $comment->text }}</p>
        <p class="comment-date">{{ $comment->date }}</p>
    </div>
</li>