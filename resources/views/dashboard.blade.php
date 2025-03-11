<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Dashboard</a>
            <span class="text-white">Welcome, <strong>{{Auth::user()->name}}</strong></span>
            <a href="{{ route('logout') }}" class="btn btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
                    <div class="card-header bg-dark text-center text-white">
                        <h1>Dashboard</h1>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <button id="addPost" class="btn btn-outline-success me-2"><i class="fas fa-plus"></i> Add New Post</button>
                        </div>
            
                        <!-- Dashboard Stats -->
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="card bg-info text-white p-3">
                                    <h4>Total Posts</h4>
                                    <h2>{{ $PostCount }}</h2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white p-3">
                                    <h4>Total Users</h4>
                                    <h2>{{ Auth::user()->count() }}</h2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-warning text-white p-3">
                                    <h4>Your Posts</h4>
                                    <h2>{{ $userPostCount }}</h2>
                                </div>
                            </div>
                        </div>
            
                        <!-- Recent Posts -->
                        <div class="mt-4">
                            <div id="messageBox" class="alert" style="display: none;"></div>
                            <h3>Recent Posts</h3>
                            <ul id="posts-list" class="list-group"></ul>
                        
                            <!-- Pagination Links -->
                            <div class="mt-3 d-flex justify-content-center">
                                <ul id="pagination-links" class="pagination"></ul>
                            </div>
                        </div>
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
                    <h5 class="modal-title">Add Post</h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" id="postTitle" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea id="postDescription" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-secondary">
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
        $(document).ready(function(){
            fetchPosts(page = 1);
            function fetchPosts(page = 1) {
                $.ajax({
                    url : "{{ route('posts.index') }}?page=" + page,
                    type : "GET",
                    dataType : "json",
                    success : function(response){
                        let posts = response.data;
                        let postsHtml = "";

                        if(posts.length > 0) {
                            $.each(posts, function(index, post) {
                                postsHtml += `
                                    <li class="list-group-item">
                                        <p><strong>Title:</strong> ${post.title}</p>
                                        <p><strong>Description:</strong> ${post.description}</p>
                                        <span class="badge bg-secondary">${new Date(post.created_at).toLocaleString()}</span>
                                    </li>`;
                            });
                        } else {
                            postsHtml = '<li class="list-group-item">No Posts Found.</li>';
                        }

                        $("#posts-list").html(postsHtml);

                        let paginationLinks = "";
                        let links = response.links;

                        $.each(links, function(index, link){
                            let activeClass = link.active ? 'active' : '';
                            paginationLinks += `<li class="page-item ${activeClass}">
                                <a class="page-link" href="#" data-page="${link.url ? link.url.split('=')[1] : '#'}">${link.label}</a>
                            </li>`;
                        });

                        $("#pagination-links").html(paginationLinks);
                    }
                });
            }

            $(document).on("click", "#pagination-links a", function(e) {
                e.preventDefault();
                let page = $(this).data("page");
                if (page) {
                    fetchPosts(page);
                }
            });

            $('#addPost').click(function () {
                $('#postTitle').val("");
                $('#postDescription').val("");
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
                        if(response.success) {
                            $('#messageBox')
                                .removeClass('alert-danger')
                                .addClass('alert alert-success')
                                .text(response.success)
                                .fadeIn();

                            setTimeout(function () {
                                $('#messageBox').fadeOut();
                            }, 3000);

                            let post = response.post;
                            let newRow = `
                                        <li class="list-group-item">
                                            <p><strong>Title:</strong> ${post.title}</p>
                                            <p><strong>Description:</strong> ${post.description}</p>
                                            <span class="badge bg-secondary">${new Date(post.created_at).toLocaleString()}</span>
                                        </li>`;
                            $('#posts-list').prepend(newRow);
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

        });
    </script>
</body>
</html>
