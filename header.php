<?php
?>
<style>
        .navbar {
            background-color: #23272a;
            padding: 10px 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ffffff;
            text-decoration: none;
        }

        .navbar-profile {
            display: flex;
            align-items: center;
            cursor: pointer;
            position: relative;
            padding: 5px;
            transition: all 0.3s ease;
        }

        .navbar-profile img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .navbar-profile .username {
            font-size: 1rem;
            font-weight: bold;
            margin-right: 10px;
        }

        .navbar-profile .discriminator {
            font-size: 0.9rem;
            color: #b9bbbe;
        }

        /* Arrow pointing down */
        .arrow-down {
            margin-left: 10px;
            font-size: 0.9rem;
            transition: transform 0.3s ease;
        }

        .arrow-down.rotate {
            transform: rotate(180deg);
        }

        /* Highlighted Profile Area */
        .navbar-profile.highlighted {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        /* Dropdown Menu */
        .dropdown-menu {
            background-color: #23272a;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            display: none;
            position: absolute;
            top: 50px;
            right: 20px;
            width: 200px;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            display: block;
            transition: background-color 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #40444b;
        }
      </style>
    <nav class="navbar">
        <a class="navbar-brand" href="#">Dashboard</a>
        <div class="navbar-profile" id="navbar-profile">
            <!-- Profile info will be dynamically inserted here -->
        </div>
        <!-- Dropdown Menu -->
        <div class="dropdown-menu" id="profile-menu">
            <a class="dropdown-item" href="#">Username: <span id="dropdown-username"></span></a>
            <a class="dropdown-item" href="#">Display Name: <span id="dropdown-displayname"></span></a>
            <a class="dropdown-item" href="#" id="logout-button">Logout</a>
            <a class="dropdown-item" href="#">Settings</a>
            <a class="dropdown-item" href="#">Support</a>
        </div>
    </nav>

<script>
  
        const fetchUserInfo = async (token) => {
            try {
                const response = await fetch('https://discord.com/api/v10/users/@me', {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                if (response.ok) {
                    return await response.json();
                } else {
                    throw new Error('Failed to fetch user info');
                }
            } catch (error) {
                console.error(error);
                return {};
            }
        };

        const fetchServerInfo = async () => {
            const token = localStorage.getItem('discord_access_token');
            if (!token) {
                alert('No token found. Please log in.');
                return;
            }
              const userInfo = await fetchUserInfo(token);
            const navbarProfile = document.getElementById('navbar-profile');
            const dropdownMenu = document.getElementById('profile-menu');
            const usernameElement = document.getElementById('dropdown-username');
            const displaynameElement = document.getElementById('dropdown-displayname');

            // Set profile info
            navbarProfile.innerHTML = `
                <img src="https://cdn.discordapp.com/avatars/${userInfo.id}/${userInfo.avatar}.png" alt="Profile Picture">
                <span class="username">${userInfo.global_name}</span><span class="arrow-down" id="arrow-down">▼</span>`;
            usernameElement.textContent = userInfo.username + "#" + userInfo.discriminator;
            displaynameElement.textContent = userInfo.username;

			
            navbarProfile.addEventListener('click', () => {
                dropdownMenu.classList.toggle('show');
			const profile = document.getElementById('navbar-profile');
            const arrow = document.getElementById('arrow-down');
            profile.classList.toggle('highlighted');
            arrow.classList.toggle('rotate');
            });

            // Close dropdown when clicked outside
            document.addEventListener('click', (e) => {
                if (!navbarProfile.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
								const profile = document.getElementById('navbar-profile');
            const arrow = document.getElementById('arrow-down');
            profile.classList.remove('highlighted');
            arrow.classList.remove('rotate');
                }
            });

            const logoutButton = document.getElementById('logout-button');
            logoutButton.addEventListener('click', () => {
                localStorage.removeItem('discord_access_token');
                window.location.reload();
            });

</script>
