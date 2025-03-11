<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Manage Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .sidebar {
            background-color: #343a40;
            color: white;
            min-height: 100vh;
            padding: 20px;
            box-shadow: 3px 0 5px rgba(0, 0, 0, 0.2);
        }
        .sidebar h4 {
            color: #f8f9fa;
            margin-bottom: 20px;
        }
        .sidebar .list-group-item {
            background-color: transparent;
            color: white;
            border: none;
            transition: background 0.3s, color 0.3s;
        }
        .sidebar .list-group-item a {
            text-decoration: none;
            color: white;
            display: block;
            padding: 10px 15px;
        }
        .sidebar .list-group-item:hover {
            background-color: #495057;
        }
        .sidebar .list-group-item.active {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .sidebar .list-group-item.active a {
            color: white;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Dashboard</a>
            <span class="text-white">Welcome, <strong>{{ Auth::user()->name }}</strong></span>
            <div>
                <button id="addPost" class="btn btn-outline-success me-2"><i class="fas fa-plus"></i> Add New Post</button>
                <a href="{{ route('logout') }}" class="btn btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <h4>Navigation</h4>
                <ul class="list-group">
                    <li class="list-group-item">
                        <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Home</a>
                    </li>        
                        @if(Gate::allows('isAdmin'))
                            <li class="list-group-item">
                                <a href="{{ route('posts.index') }}"><i class="fas fa-list"></i> See All Posts</a>
                            </li>
                        @endif
            
                    <li class="list-group-item">
                        <a href="{{ route('userposts.page') }}"><i class="fas fa-user-edit"></i> Your Posts</a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="card mt-3">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                        <h2 class="mb-0">Manage Posts</h2>
                        <!-- Search Form -->
                        <div class="d-flex">
                            <input type="text" id="searchInput" name="search" class="form-control me-2" placeholder="Search Post..." style="max-width: 500px;">
                            <button id="searchBtn" class="btn btn-outline-primary"><i class="fas fa-search"></i> Search</button>
                        </div>
                    </div>

                    <div id="messageBox" class="alert" style="display: none;"></div>
                    
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="postsTableBody">

                            </tbody>
                        </table>
                        <button id="loadMore" class="btn btn-primary">Load More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Modal for View & Edit -->
    <div class="modal fade" id="postModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title">View Post</h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="postId">
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" id="postTitle" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea id="postDescription" class="form-control" rows="3" readonly></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-secondary">
                    <button class="btn btn-primary" id="updatePost" style="display: none;">Save Changes</button>
                    <button class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button id="savePost" class="btn btn-primary">Save Post</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; 2025 CRUD Website. All Rights Reserved!...</p>
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        $(document).ready(function () {
            let page = 1;

            function fetchPosts() {
                $.ajax({
                    url: "{{ route('posts.index') }}?page=" + page,
                    method: "GET",
                    success: function (response) {
                        let posts = response.data;
                        let tableBody = $('#postsTableBody');

                        posts.forEach(post => {
                            tableBody.append(`
                                <tr>
                                    <td>${post.id}</td>
                                    <td>${post.title}</td>
                                    <td>${post.user_id}</td>
                                    <td>
                                        @if(Gate::allows('isAdmin'))
                                            <button class="btn btn-success btn-sm viewPost" data-id="${post.id}">View</button>
                                            <button class="btn btn-primary btn-sm editPost" data-id="${post.id}">Edit</button>
                                            <button class="btn btn-danger btn-sm deletePost" data-id="${post.id}">Delete</button>
                                        @endif
                                    </td>
                                </tr>
                            `);
                        });

                        $('#postsTableBody').append(postsHtml);

                        if (!response.next_page) {
                            $('#loadMore').hide();
                        }
                    }
                });
            }

            fetchPosts();

            $('#loadMore').click(function () {
                page++;
                fetchPosts();
            });

            // View Post
            $(document).on('click', '.viewPost', function () {
                let id = $(this).data('id');
                $.ajax({
                    url: "/posts/" + id,
                    type: "GET",
                    success: function (post) {
                        if (post) {
                            $('#postId').val(post.id);
                            $('#postTitle').val(post.title);
                            $('#postDescription').val(post.description || "No Description Available!...");
                            $('#postTitle, #postDescription').attr('readonly', true);
                            $('#updatePost').hide();
                            $('#savePost').hide();
                            $('#postModal').modal('show');
                        }
                    },
                    error: function () {
                        alert("Post Not Found!...");
                    }
                });
            });

            // Edit Post
            $(document).on('click', '.editPost', function () {
                let id = $(this).data('id');
                $.ajax({
                    url: "/posts/" + id,
                    type: "GET",
                    success: function (post) {
                        if (post) {
                            $('#postId').val(post.id);
                            $('#postTitle').val(post.title);
                            $('#postDescription').val(post.description);
                            $('#postTitle, #postDescription').attr('readonly', false);
                            $('#updatePost').show();
                            $('#savePost').hide();
                            $('#postModal').modal('show');
                        }
                    }
                });
            });

            // Update Post
            $('#updatePost').click(function (event) {
                event.preventDefault();
                
                let id = $('#postId').val();
                let title = $('#postTitle').val();
                let description = $('#postDescription').val();

                $.ajax({
                    url: "/posts/" + id,
                    type: "PUT",
                    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                    data: { title: title, description: description },
                    success: function (response) {
                        if (response.success) {
                            $('#messageBox')
                                .removeClass('alert-danger')
                                .addClass('alert alert-success')
                                .text(response.success)
                                .fadeIn();
                            
                            setTimeout(function () {
                                $('#messageBox').fadeOut();
                                
                            }, 3000);
                            location.reload();
                            $('#postModal').modal('hide');
                        }
                    },
                    error: function (xhr) {
                        $('#messageBox')
                            .removeClass('alert-success')
                            .addClass('alert alert-danger')
                            .text('Something Went Wrong! Please Try Again!...')
                            .fadeIn();
                        
                        setTimeout(function () {
                            $('#messageBox').fadeOut();
                        }, 3000);
                    }
                });
            });

            // Delete Post
            $(document).on('click', '.deletePost', function () {
                if (!confirm('Are you sure?')) return;

                let id = $(this).data('id');

                $.ajax({
                    url: "{{ url('/posts') }}/" + id,
                    method: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function (response) {
                        if (response.success) {
                            $('#messageBox')
                                .removeClass('alert-danger')
                                .addClass('alert alert-success')
                                .text(response.success)
                                .fadeIn();
                            
                            setTimeout(function () {
                                $('#messageBox').fadeOut();
                                location.reload();
                            }, 3000);
                        }
                    },
                    error: function (xhr) {
                        $('#messageBox')
                            .removeClass('alert-success')
                            .addClass('alert alert-danger')
                            .text('Something Went Wrong! Please Try Again!...')
                            .fadeIn();
                        
                        setTimeout(function () {
                            $('#messageBox').fadeOut();
                        }, 3000);
                    }
                });
            });

            $('#addPost').click(function () {
                $('#postId').val("");
                $('#postTitle').val("");
                $('#postDescription').val("");
                $('#postTitle, #postDescription').attr('readonly', false);
                $('#updatePost').hide();
                $('#savePost').show();
                $('#postModal').modal('show');
            });

            // New Post
            $('#savePost').click(function (event) {
                event.preventDefault();

                let title = $('#postTitle').val();
                let description = $('#postDescription').val();
                let _token = "{{ csrf_token() }}";

                $.ajax({
                    url: "{{ route('posts.store') }}",
                    type: "POST",
                    data: { title: title, description: description, _token: _token },
                    success: function (response) {
                        if (response.success) {
                            $('#messageBox')
                                .removeClass('alert-danger')
                                .addClass('alert alert-success')
                                .text(response.success)
                                .fadeIn();

                            setTimeout(function () {
                                $('#messageBox').fadeOut();
                            }, 3000);

                            let newRow = `
                                <tr id="post-${response.post.id}">
                                    <td>${response.post.id}</td>
                                    <td>${response.post.title}</td>
                                    <td>${response.post.user_id}</td>
                                    <td>
                                        <button class="btn btn-success btn-sm viewPost" data-id="${response.post.id}">View</button>
                                        <button class="btn btn-primary btn-sm editPost" data-id="${response.post.id}">Edit</button>
                                        <button class="btn btn-danger btn-sm deletePost" data-id="${response.post.id}">Delete</button>
                                    </td>
                                </tr>
                            `;
                            $('#postsTableBody').prepend(newRow);

                            $('#postModal').modal('hide');
                        }
                    },
                    error: function (xhr) {
                        $('#messageBox')
                            .removeClass('alert-success')
                            .addClass('alert alert-danger')
                            .text('Something Went Wrong! Please Try Again!...')
                            .fadeIn();

                        setTimeout(function () {
                            $('#messageBox').fadeOut();
                        }, 3000);
                    }
                });
            });

            // search form
            function performSearch() {
                let query = $('#searchInput').val();

                $.ajax({
                    url: "{{ route('posts.search') }}",
                    type: "GET",
                    data: { search: query },
                    success: function (response) {
                        let resultsHtml = '';

                        if (response.data && response.data.length > 0) {
                            response.data.forEach(function (post) {
                                resultsHtml += `
                                    <tr>
                                        <td>${post.id}</td>
                                        <td>${post.title}</td>
                                        <td>${post.user_id}</td>
                                        <td>
                                            <button class="btn btn-success btn-sm viewPost" data-id="${post.id}">View</button>
                                            <button class="btn btn-primary btn-sm editPost" data-id="${post.id}">Edit</button>
                                            <button class="btn btn-danger btn-sm deletePost" data-id="${post.id}">Delete</button>
                                        </td>
                                    </tr>
                                `;
                            });
                        } else {
                            resultsHtml = `<tr><td colspan="4" class="text-center text-muted">No Posts Found!...</td></tr>`;
                        }

                        $('#postsTableBody').html(resultsHtml);
                    },
                    error: function () {
                        alert('Search Failed! Please Try Again!...');
                    }
                });
            }


            $('#searchBtn').click(function (event) {
                event.preventDefault();
                performSearch();
            });

            $('#searchInput').on('input', function () {
                performSearch();
            });
        });
    </script>

</body>
</html>
