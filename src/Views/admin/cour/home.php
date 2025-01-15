<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-base-200">
    <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col p-4">
            <label for="my-drawer-2" class="btn btn-primary drawer-button lg:hidden mb-4">Open menu</label>
            <h1 class="text-3xl font-bold mb-6">Teacher Dashboard</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="stats shadow">
                    <div class="stat">
                        <div class="stat-figure text-primary">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                        <div class="stat-title">Total Courses</div>
                        <div class="stat-value">25</div>
                        <div class="stat-desc">21% more than last month</div>
                    </div>
                </div>
                
                <div class="stats shadow">
                    <div class="stat">
                        <div class="stat-figure text-secondary">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div class="stat-title">Students</div>
                        <div class="stat-value">500</div>
                        <div class="stat-desc">5% more than last month</div>
                    </div>
                </div>
                
                <div class="stats shadow">
                    <div class="stat">
                        <div class="stat-figure text-accent">
                            <i class="fas fa-folder fa-2x"></i>
                        </div>
                        <div class="stat-title">Categories</div>
                        <div class="stat-value">8</div>
                        <div class="stat-desc">2 new categories added</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-base-100 p-6 rounded-box shadow-xl">
                <h2 class="text-2xl font-semibold mb-4">Your Courses</h2>
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Introduction to Programming</td>
                                <td>Computer Science</td>
                                <td>
                                    <button class="btn btn-sm btn-info mr-2">Edit</button>
                                    <button class="btn btn-sm btn-error">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Web Development Basics</td>
                                <td>Web Technologies</td>
                                <td>
                                    <button class="btn btn-sm btn-info mr-2">Edit</button>
                                    <button class="btn btn-sm btn-error">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Data Structures and Algorithms</td>
                                <td>Computer Science</td>
                                <td>
                                    <button class="btn btn-sm btn-info mr-2">Edit</button>
                                    <button class="btn btn-sm btn-error">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary">Add New Course</button>
                </div>
            </div>
        </div> 
        <div class="drawer-side">
            <label for="my-drawer-2" class="drawer-overlay"></label> 
            <ul class="menu p-4 w-80 h-full bg-base-100 text-base-content">
                <li><a class="font-semibold text-lg mb-4">Teacher Name</a></li>
                <li><a><i class="fas fa-home mr-2"></i>Dashboard</a></li>
                <li><a><i class="fas fa-book mr-2"></i>Courses</a></li>
                <li><a><i class="fas fa-users mr-2"></i>Students</a></li>
                <li><a><i class="fas fa-folder mr-2"></i>Categories</a></li>
                <li><a><i class="fas fa-cog mr-2"></i>Settings</a></li>
                <li><a><i class="fas fa-sign-out-alt mr-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</body>
</html>
