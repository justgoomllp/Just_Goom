<div class="admin-listing-actions">
    <a href="{{ $editUrl }}" class="btn btn-outline-primary btn-sm">Edit</a>
    <form action="{{ $deleteUrl }}" method="POST" class="admin-delete-form" data-confirm-title="{{ $confirmTitle }}" data-confirm-text="{{ $confirmText }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
    </form>
</div>
