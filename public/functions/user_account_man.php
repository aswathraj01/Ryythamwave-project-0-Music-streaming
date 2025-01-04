<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RYYTHAMWAVE</title>
    <link rel="icon" type="image/x-icon" href="public/assets/images/logo.png">
    <style>
        @font-face {
    font-family: lato;
    src: url(public/assets/fonts/Lato-Regular.ttf);
}


*{
    box-sizing: border-box;
}

body{
    width: 350px;
    font-family: lato, sans-serif, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0%;
    padding: 0%;
}

body img{
    width: 100%;
}

body a{
    text-decoration: none;
}

header{
    display: flex;
}

/* Main header css*/

header .active{
    border-bottom: solid 4px rgb(0, 255, 128);
}

.logo-head{
    flex: 1;
    max-width: 100px;
}

.header-div{
    flex: auto;

}

.main-nav{
    display: flex;
}

.nav-item{
    padding: 10px;
    text-align: center;
}

.main-title{
    padding: 10px;
}

.dropdown{
    position: relative;
}

.dropdown-list{
    position: absolute;
    border: solid thin rgb(182, 182, 182);
    background-color: white;
    margin-top: 10px;
}
/* Dropdown styles */
.dropdown-list {
    position: absolute;
    border: solid thin rgb(182, 182, 182);
    background-color: white;
    margin-top: 10px;
    display: none; /* Hide the dropdown by default */
}

.dropdown:hover .dropdown-list,
.dropdown-list:hover {
    display: block; /* Show the dropdown when hovering over the Category or the dropdown list */
}

.dropdown-list .nav-item {
    padding: 10px;
}

.dropdown-list .nav-item:hover {
    background-color: #00ff80;
}

/*bg image*/
.image-bg{
    height: 700px;
    object-fit: cover;
}

/* Visited Link*/

a:visited{
    color: black;
}
a{
    color: black;
}


    </style>
</head>
<body>

    <header>
        <div class="logo-head">
            <a href="../../home.html"><img class="logo" src="../assets/images/logo.png"></a>
        </div>
        <div class="header-content">
            <div class="main-title">RYYTHAMWAVE</div>
            <nav class="main-nav">
                <div class="nav-item active"><a href="../../home.html">Home</a></div>
                <div class="nav-item dropdown">
                    <a href="">Music</a>
                    <div class="dropdown-list">
                        <div class="nav-item"><a href="#">Tracks</a></div>
                        <div class="nav-item"><a href="#">Album</a></div>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="#">Category</a>
                    <div class="dropdown-list">
                        <div class="nav-item"><a href="#">Category1</a></div>
                        <div class="nav-item"><a href="#">Category2</a></div>
                        <div class="nav-item"><a href="#">Category3</a></div>
                        <div class="nav-item"><a href="#">Category4</a></div>
                    </div>
                </div>                
                <div class="nav-item"><a href="">Artists</a></div>
                <div class="nav-item"><a href="">About</a></div>
                <div class="nav-item dropdown"><a href="">Hi user</a>
                    <div class="dropdown-list">
                        <div class="nav-item"><a href="">Account</a></div>
                        <div class="nav-item"><a href="../../adminlogin.php">Admin</a></div>
                        <div class="nav-item"><a href="../../Login.html">Logout</a></div>
                    </div>
                </div>
            </nav>
            </div>
    </header>

    <section>
        <img class="image-bg" src="../assets/images/background images.jpg" alt="">
    </section>
    <div class="form-container">
            <h2>Add Album</h2>
            <form action="user_account_man.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="username">User Name</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="text" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="mobile_no">Mobile No</label>
                    <input type="text" id="mobile_no" name="mobile_no" required>
                </div>
                <div class="form-group">
                    <label for="user_profile">Profile Pic</label>
                    <input type="file" id="user_profile" name="user_profile" accept="image/*" required>
                </div>
                <button type="submit" class="submit-btn">Update</button>
            </form>
        </div>
    </div>