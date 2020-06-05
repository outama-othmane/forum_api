<style type="text/css">
    * {
        box-sizing: border-box;
    }
    body {
        font-size: 15px;
        font-weight: 400;
        font-family: Lato;
        padding: 0;
        margin: 0;
    }
    a{
        color: #2196f3;
        text-decoration: none;
    }
    .discussion {
        display: flex;
        cursor: pointer;
        align-items: center;
        flex-direction: row;
        padding: .75rem 1.5rem;
    }
    .discussion:hover {
        background-color: #f6f6f6;
    }
    .avatar {
        width: auto;
        margin-right: 1.5rem;
        margin-bottom: 0;
    }

    .avatar > a {
        width: 50px;
    }

    .avatar > a > img {
        position: relative;
        border-radius: 50%;
        max-height: 50px;
        top: 1.5px;
        width: 50px;
        max-width: 100%;
    }
    .content {
        width: 83.33333%;
        margin-bottom: 0;
        padding-right: 3rem;
    }

    .content > h4 {
        margin: 0;
        margin-bottom: .25rem;
        font-size: 1rem;
        word-break: break-word;
    }
    .content>h4+div {
        font-size: .75rem;
        color: #8795a1;
    }
    .content>h4+div>a{
        text-transform: uppercase;
        font-weight: 700;
    }
    .content>h4+div>span>a{
        color: #8795a1;
    }

    .meta {
        display: flex;
        align-items: center;
        flex-direction: row-reverse;
        text-align: center;
        position: relative;
        margin-left: auto;
    }
    .badge {
        background-color: #2196f3;
        color: #fff; 
        border-radius: 4px; 
        padding: 4px 5px; 
        font-size: 12px;
        margin-left: 1.25rem;
        width: 6rem;
    }

    .meta > div {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 1rem;
    }
    .meta>div>div {
        margin-right: .25rem;
    }
    .meta>div>span{
        color: #8795a1;
        text-align: left;
        font-weight: 600;
    }
    svg {
        vertical-align: middle;
    }
</style>

@foreach($discussions as $discussion)
    <div class="discussion">
        <div class="avatar">
            <a href="#">
                <img src="https://i1.wp.com/s3.amazonaws.com/laracasts/images/forum/avatars/avatar-{{ rand(1, 10) }}.png?ssl=1" alt="avatar" title="{{ $discussion->lastPost->user->name }}" />
            </a>
        </div>
        <div class="content">
            <h4>
                {{ $discussion->title }}
            </h4>
            <div>
                <a href="#">
                    {{ $discussion->lastPost->user->name }}
                </a>
                {{ $discussion->posts_count == 1 ? 'posted'  : 'replied' }}
                <span>
                    <a href="#">{{ $discussion->lastPost->created_at->diffForHumans() }}</a>
                </span>
            </div>
        </div>
        <div class="meta">
            <a class="badge">{{ $discussion->channel->name }}</a>
            <div>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="19" viewBox="0 0 15 10" class="tw-relative" style="top: -2px;"><path fill="#78909C" fill-rule="evenodd" d="M7.5 0C3.344 0 0 2.818 0 6.286c0 1.987 1.094 3.757 2.781 4.914l.117 2.35c.022.438.338.58.704.32l2.023-1.442c.594.144 1.219.18 1.875.18 4.156 0 7.5-2.817 7.5-6.285C15 2.854 11.656 0 7.5 0z" opacity=".5"></path></svg>
                </div> 
                <span>{{ $discussion->posts_count-1 }}</span>
            </div>
        </div>
    </div>
@endforeach

{{ $discussions->appends(request()->all())->links() }}