<div class="tab-pane fade" id="settings">
                                <div class="settings">
                                    <div class="profile">
                                        <img class="avatar-xl" src="dist/img/avatars/avatar-male-1.jpg" alt="avatar">

                                        <h1><a
                                                href="#"><?php echo $userData['FirstName'] . " " . $userData['LastName'];?></a>
                                        </h1>
                                        <span><?php echo $userData['UserName']?></span>
                                        <div class="stats">
                                            <div class="item">
                                                <h2><?php print(ContactController::getContactsCount()); ?></h2>
                                                <h3>Contact</h3>
                                            </div>
                                            <div class="item">
                                                <h2>305</h2>
                                                <h3>Chats</h3>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="categories" id="accordionSettings">
                                        <h1>Settings</h1>
                                        <!-- Start of My Account -->
                                        <div class="category">
                                            <a href="#" class="title collapsed" id="headingOne" data-toggle="collapse"
                                                data-target="#collapseOne" aria-expanded="true"
                                                aria-controls="collapseOne">
                                                <i class="material-icons md-30 online">person_outline</i>
                                                <div class="data">
                                                    <h5>My Account</h5>
                                                    <p>Update your profile details</p>
                                                </div>
                                                <i class="material-icons">keyboard_arrow_right</i>
                                            </a>
                                            <div class="collapse" id="collapseOne" aria-labelledby="headingOne"
                                                data-parent="#accordionSettings">
                                                <div class="content">

                                                    You can set a default image to display if the user doesn't have an
                                                    avatar image uploaded. Here's how to modify your code to achieve
                                                    this:

                                                    Updated Code
                                                    php
                                                    Copy code
                                                    <div class="upload">
                                                        <div class="data">
                                                            <?php 
            // Set the default avatar image path
            $defaultAvatar = "dist/img/avatars/avatar-male-1.jpg"; // Path to default avatar image
            // Check if the user has a profile picture
            $userAvatar = isset($userData['ProfilePictureURL']) && $userData['ProfilePictureURL'] !== null ? $userData['ProfilePictureURL'] : $defaultAvatar;
        ?>
                                                            <img class="avatar-xl"
                                                                src="<?php echo htmlspecialchars($userAvatar); ?>"
                                                                alt="User Avatar">
                                                            <label>
                                                                <input type="file" name="avatar" accept="image/*">
                                                                <span class="btn button">Upload avatar</span>
                                                            </label>
                                                        </div>
                                                        <p>For best results, use an image at least 256px by 256px in
                                                            either .jpg or .png format!</p>
                                                    </div>
                                                    <form action="MyAccountApply.php" method="post">
                                                        <div class="parent">
                                                            <div class="field">
                                                                <label for="firstName">First name <span>*</span></label>
                                                                <input type="text" class="form-control" id="firstName"
                                                                    name="firstName" placeholder="First name"
                                                                    value="<?php echo $userData['FirstName']?>"
                                                                    required>
                                                            </div>
                                                            <div class="field">
                                                                <label for="lastName">Last name <span>*</span></label>
                                                                <input type="text" class="form-control" id="lastName"
                                                                    name="lastName" placeholder="Last name"
                                                                    value="<?php echo $userData['LastName']?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <label for="email">Email <span>*</span></label>
                                                            <input type="email" class="form-control" id="email"
                                                                name="email" placeholder="Enter your email address"
                                                                value="<?php echo $userData['Email']?>" readonly>
                                                        </div>
                                                        <div class="field">
                                                            <label for="phone">Phone</label>
                                                            <input type="tel" class="form-control" id="phone"
                                                                name="phone" placeholder="Enter your phone number"
                                                                value="<?php echo $userData['Phone']?>">
                                                        </div>
                                                        <div class="field">
                                                            <label for="gender">Gender</label>
                                                            <select class="form-control" id="gender" name="gender">
                                                                <option value="M"
                                                                    <?php echo isset($userData['Gender']) && $userData['Gender'] === 'M' ? 'selected' : ''; ?>>
                                                                    Male</option>
                                                                <option value="F"
                                                                    <?php echo isset($userData['Gender']) && $userData['Gender'] === 'F' ? 'selected' : ''; ?>>
                                                                    Female</option>
                                                                <!-- Add more options if needed -->
                                                            </select>
                                                        </div>
                                                        <div class="field">
                                                            <label for="dateOfBirth">Date of Birth</label>
                                                            <input type="date" class="form-control" id="dateOfBirth"
                                                                name="dateOfBirth"
                                                                value="<?php echo isset($userData['DateOfBirth']) ? htmlspecialchars($userData['DateOfBirth']) : ''; ?>"
                                                                required>
                                                        </div>
                                                        <div class="field">
                                                            <label for="country">Country</label>
                                                            <select id="inputCountry" name="CountryID"
                                                                class="form-control" required>
                                                                <option value="" disabled>Select Country</option>
                                                                <!-- Default option -->
                                                                <?php
            foreach ($countries as $c) {
                // Check if the current country ID matches the user's country ID
                $selected = (isset($userData['CountryID']) && $userData['CountryID'] == $c['CountryID']) ? 'selected' : '';
                echo '<option value="' . htmlspecialchars($c['CountryID']) . '" ' . $selected . '>' . htmlspecialchars($c['CountryName']) . '</option>';
            }
        ?>
                                                            </select>
                                                        </div>
                                                        <button type="submit" class="btn button w-100">Apply</button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- End of My Account -->
                                        <!-- Start of Chat History -->

                                        <!-- End of Chat History -->
                                        <!-- Start of Notifications Settings -->

                                        <!-- End of Notifications Settings -->
                                        <!-- Start of Connections -->
                                        <!-- End of Connections -->
                                        <!-- Start of Appearance Settings -->
                                        <div class="category">
                                            <a href="#" class="title collapsed" id="headingFive" data-toggle="collapse"
                                                data-target="#collapseFive" aria-expanded="true"
                                                aria-controls="collapseFive">
                                                <i class="material-icons md-30 online">colorize</i>
                                                <div class="data">
                                                    <h5>Appearance</h5>
                                                    <p>Customize the look of Swipe</p>
                                                </div>
                                                <i class="material-icons">keyboard_arrow_right</i>
                                            </a>
                                            <div class="collapse" id="collapseFive" aria-labelledby="headingFive"
                                                data-parent="#accordionSettings">
                                                <div class="content no-layer">
                                                    <div class="set">
                                                        <div class="details">
                                                            <h5>Turn Off Lights</h5>
                                                            <p>The dark mode is applied to core areas of the app that
                                                                are normally displayed as light.</p>
                                                        </div>
                                                        <label class="switch">
                                                            <input type="checkbox">
                                                            <span class="slider round mode"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End of Appearance Settings -->
                                        <!-- Start of Language -->
                                        <div class="category">
                                            <a href="#" class="title collapsed" id="headingSix" data-toggle="collapse"
                                                data-target="#collapseSix" aria-expanded="true"
                                                aria-controls="collapseSix">
                                                <i class="material-icons md-30 online">language</i>
                                                <div class="data">
                                                    <h5>Language</h5>
                                                    <p>Select preferred language</p>
                                                </div>
                                                <i class="material-icons">keyboard_arrow_right</i>
                                            </a>
                                            <div class="collapse" id="collapseSix" aria-labelledby="headingSix"
                                                data-parent="#accordionSettings">
                                                <div class="content layer">
                                                    <div class="language">
                                                        <label for="country">Language</label>
                                                        <select class="custom-select" id="country" required>
                                                            <option value="">Select an language...</option>
                                                            <option>Arabic, JO</option>
                                                            <option>English, US</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End of Language -->
                                        <!-- Start of Privacy & Safety -->
                                        <div class="category">
                                            <a href="#" class="title collapsed" id="headingSeven" data-toggle="collapse"
                                                data-target="#collapseSeven" aria-expanded="true"
                                                aria-controls="collapseSeven">
                                                <i class="material-icons md-30 online">lock_outline</i>
                                                <div class="data">
                                                    <h5>Privacy & Safety</h5>
                                                    <p>Control your privacy settings</p>
                                                </div>
                                                <i class="material-icons">keyboard_arrow_right</i>
                                            </a>
                                            <div class="collapse" id="collapseSeven" aria-labelledby="headingSeven"
                                                data-parent="#accordionSettings">
                                                <div class="content no-layer">
                                                    <div class="set">
                                                        <div class="details">
                                                            <h5>Data to Improve</h5>
                                                            <p>This settings allows us to use and process information
                                                                for analytical purposes.</p>
                                                        </div>
                                                        <label class="switch">
                                                            <input type="checkbox">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                    <div class="set">
                                                        <div class="details">
                                                            <h5>Data to Customize</h5>
                                                            <p>This settings allows us to use your information to
                                                                customize Swipe for you.</p>
                                                        </div>
                                                        <label class="switch">
                                                            <input type="checkbox">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                    <form action="PrivacyApply.php" method="post">

                                                        <div class="field">
                                                            <label for="Password">Current Password</label>
                                                            <input type="password" class="form-control" id="Password"
                                                                name="password" placeholder="Enter a Current password"
                                                                required>
                                                        </div>
                                                        <div class="field">
                                                            <label for="newPassword">New Password</label>
                                                            <input type="password" class="form-control" id="newPassword"
                                                                name="newPassword" placeholder="Enter a new password"
                                                                required>
                                                        </div>
                                                        <button class="btn btn-link w-100" type="button">Delete
                                                            Account</button>
                                                        <button type="submit" class="btn button w-100">Apply</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End of Privacy & Safety -->
                                        <!-- Start of Logout -->
                                        <div class="category">
                                            <a href="auth/sign-in.php" class="title collapsed">
                                                <i class="material-icons md-30 online">power_settings_new</i>
                                                <div class="data">
                                                    <h5>Power Off</h5>
                                                    <p>Log out of your account</p>
                                                </div>
                                                <i class="material-icons">keyboard_arrow_right</i>
                                            </a>
                                        </div>
                                        <!-- End of Logout -->
                                    </div>
                                </div>
                            </div>