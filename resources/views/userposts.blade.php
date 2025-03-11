<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>See Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <span class="text-white">Welcome, <strong>{{ Auth::user()->name }}</strong></span>
            <a href="{{ route('logout') }}" class="btn btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
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
            <div class="col-md-9">
                <div class="card mt-3">
                    <div class="card-header bg-dark text-white py-3">
                        <h2 class="mb-0">Post Details</h2>
                    </div>
                    <div class="card-body">
                        <div id="postsTableBody">

                        </div>
                        <button id="loadMore" class="btn btn-primary">Load More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; 2025 CRUD Website. All Rights Reserved!...</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        $(document).ready(function () {
            let page = 1;

            function fetchUserPosts() {
                $.ajax({
                    url: "{{ route('userposts.data') }}?page=" + page,
                    method: "GET",
                    success: function (response) {
                        let posts = response.data;
                        let tableBody = $('#postsTableBody');

                        if (posts.length === 0 && page === 1) {
                            tableBody.append("<p class='text-danger'>No posts found.</p>");
                            $('#loadMore').hide();
                            return;
                        }

                        posts.forEach(post => {
                            let userName = post.user ? post.user.name : 'Unknown User';
                            let roleType = post.user && post.user.role ? post.user.role : 'Unknown Role';

                            tableBody.append(`
                                <h3>${post.title}</h3>
                                <p><strong>${userName}</strong> (${roleType})</p>
                                <p>${post.description}</p>
                                <hr>
                            `);
                        });

                        if (!response.next_page_url) {
                            $('#loadMore').hide();
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching posts:", error);
                    }
                });
            }

            fetchUserPosts();

            $('#loadMore').click(function () {
                page++;
                fetchUserPosts();
            });
        });
    
    </script>
</body>
</html>
