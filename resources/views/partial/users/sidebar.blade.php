<div class="wn__sidebar">

    <aside class="widget recent_widget">
        <ul>
            <li class="list-group-item">
                <img src="{{ asset('assets/users/danielle-avery.png') }}" alt="{{ auth()->user()->name }}">
            </li>

            <li class="list-group-item"><a href="{{ route('frontend.dashboard') }}">My Posts</a></li>
            <li class="list-group-item"><a href="{{ route('users.post.create') }}">Create Post</a></li>
            <li class="list-group-item"><a href="{{ route('users.comments') }}">Manage Commments</a></li>
            <li class="list-group-item"><a href="{{ route('users.edit_info') }}">Update Information</a></li>
            <li class="list-group-item">
                <a href="{{ route('frontend.logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('frontend.logout') }}"
                    method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </aside>
</div>
