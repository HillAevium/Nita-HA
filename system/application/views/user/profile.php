            <h2>Company Information</h2>
            <div class="info_box">
                <p>
                <?php
                $br = "<br />";
                echo $profile['name'] . $br;
                echo $profile['address'] . $br;
                echo $profile['city'] . ', ' . $profile['state'] . $br;
                echo $profile['zip'] . $br . $br;
                echo "P: " . $profile['phone'] . $br;
                echo "F: " . $profile['fax'];
                ?>
                </p>
            </div>
            <div class="gray_line"></div>
            <?php if($display == 'multi'): ?>
            <h2>Company Profiles</h2>
            <?php foreach($userProfiles as $user): ?>
            <div class="info_box">
                <p>
                <?php echo $user['name']; ?><br />
                <?php echo $user['type']; ?>
                </p>
            </div>
            <div class="gray_line"></div>
            <?php endforeach; ?>
            <?php endif; ?>