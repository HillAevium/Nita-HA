<?php
if($display == 'multi') {
    $top['title'] = "Company Information";
    $top['event'] = 'controller.onEditAccount();';
    $bottom['title'] = "Company Profiles";
} else {
    $top['title'] = "Your Profile";
    $top['event'] = 'controller.onEditProfile();';
    $bottom['title'] = "Company Information";
}
?>
            <h2><?php echo $top['title']; ?></h2>
            <div class="info_box" onclick="<?php echo $top['event']; ?>">
                <p>
                <?php
                $br = "<br />";
                if($display == 'single') {
                    echo $profile->salutation.' '.$profile->firstName.' '.$profile->middleInitial.' '.$profile->lastName.' '.$profile->suffix.$br;
                    echo $profile->billingAddress1 . $br;
                    echo $profile->billingCity . ', ' . $profile->billingState . $br;
                    echo $profile->billingZip . $br . $br;
                    echo "P: " . $profile->phone . $br;
                    echo "P: " . $profile->phone2;
                } else {
                    echo $account->name . $br;
                    echo $account->billingAddress1 . $br;
                    echo $account->billingCity . ', ' . $account->billingState . $br;
                    echo $account->billingZip . $br . $br;
                    echo "P: " . $account->phone1 . $br;
                    echo "F: " . $account->fax;
                }
                ?>
                </p>
            </div>
            <div style="position:relative;height:55px;">
                <h2 style="position:absolute;left:0px;bottom:0px;"><?php echo $bottom['title']; ?></h2>
                <?php if($display == 'multi'): ?>
                <a id="add_profile" style="position:absolute; right:0px; bottom:5px;" href=""></a>
                <?php endif; ?>
            </div>
            <?php if($display == 'multi'): ?>
            <?php for($i = 0; $i < count($userProfiles); $i++):?>
            <?php $user = $userProfiles[$i]?>
            <?php $event = 'controller.onEditProfile('.$i.')'; ?>
            <div class="info_box" onclick="<?php echo $event; ?>">
                <p>
                <?php echo $user->salutation.' '.$user->firstName.' '.$user->middleInitial.' '.$user->lastName.' '.$user->suffix; ?><br />
                <?php echo $user->userType; ?>
                </p>
            </div>
            <?php endfor; ?>
            <?php else: ?>
            <div class="info_box" onclick="controller.onEditAccount();">
                <p>
                <?php
                $br = "<br />";
                echo $account->name . $br;
                echo $account->billingAddress1 . $br;
                echo $account->billingCity . ', ' . $account->billingState . $br;
                echo $account->billingZip . $br . $br;
                echo "P: " . $account->phone1 . $br;
                echo "F: " . $account->fax;
                ?>
                </p>
            </div>
            <?php endif; ?>