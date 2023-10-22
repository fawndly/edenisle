<?php $this->load->library('recaptcha'); ?>
<div id="zoomit">
    <div class="ta-left">
        <h2>
            <?php if(validation_errors() || (isset($wrong_un_or_pw) && ($wrong_un_or_pw === TRUE))): ?>
                <img src="/images/icons/frown.png" width="22" height="22" />
                Oh no! Double check and give it another try
            <?php else: ?>
                <img src="/images/icons/happy.png" width="22" height="22" />
                <?php if ($this->input->get('avatar')): ?>
                    Last step! Fill out your account information.
                <?php else: ?>
                    Deciding to join us? Great decision!
                <?php endif; ?>
            <?php endif; ?>
        </h2>
        <div id="notice"></div>
        <?php if (validation_errors()) {
            $this->load->view('partials/notices/error.php', array('data' => validation_errors()));
        }
        if(isset($error_msg) && strlen($error_msg) > 0) {
            $this->load->view('partials/notices/error.php', array('data' => "<li>{$error_msg}</li>"));
        } ?>
        <div id="avatarmaker">
            <div>
                <small class="help-block text-center">Let's get started by choosing your avatar gender, skin color and some basic items!</small>
            </div>
            <div style="float:left; width:180px; margin-left:-25px; text-align:center">
                <div id="avatar"></div>
                <ul class="choice_select" style="margin: 4px;">
                    <li><a href="#" class="base-color equippable" data-item-id="1" data-item-type="base" data-item-gender="u" data-item-layer="2" style="background:#fee3d4;"></a></li>
                    <li><a href="#" class="base-color equippable" data-item-id="3" data-item-type="base" data-item-gender="u" data-item-layer="2" style="background:#f7c4ab;"></a></li>
                    <li><a href="#" class="base-color equippable" data-item-id="2" data-item-type="base" data-item-gender="u" data-item-layer="2" style="background:#db9f83;"></a></li>
                    <li><a href="#" class="base-color equippable" data-item-id="4" data-item-type="base" data-item-gender="u" data-item-layer="2" style="background:#964227;"></a></li>
                    <li><a href="#" class="base-color equippable" data-item-id="5" data-item-type="base" data-item-gender="u" data-item-layer="2" style="background:#561c08;"></a></li>
                    <li><a href="#" class="base-color equippable" data-item-id="6" data-item-type="base" data-item-gender="u" data-item-layer="2" style="background:#561c08;"></a></li>
                </ul>
                <button id="toggleGender" title="toggle avatar gender">Gender</button>
            </div>
            <div class="left" style="width:365px;">
                <div class="item-container">
                    <div class="eq-row">
                        <h5>Hairstyle</h5>
                        <ul class="choice_select">
                            <?php foreach ($signup_options['hairs'] as $item): ?>
                                <li><a class="equippable" title="<?=$item['name']?>" data-item-type="hair" data-item-id="<?=$item['item_id']?>" data-item-layer="20" data-item-gender="<?=$item['gender']?>" href="#"><img src="/images/items/<?= $item['thumb'] ?>" width="42" height="42"></a></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                    <div class="eq-row">
                        <h5>Eyes</h5>
                        <ul class="choice_select">
                            <?php foreach ($signup_options['eyes'] as $item): ?>
                                <li><a class="equippable" title="<?=$item['name']?>" data-item-type="eye" data-item-id="<?=$item['item_id']?>" data-item-layer="15" data-item-gender="<?=$item['gender']?>" href="#"><img src="/images/items/<?= $item['thumb'] ?>" width="42" height="42"></a></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                    <div class="eq-row">
                        <!--<div class="col2 col2-1">
                            <h5>Underwear</h5>
                            <ul class="choice_select">
                                <?php foreach ($signup_options['underwear'] as $item): ?>
                                    <li><a class="equippable" title="<?=$item['name']?>" data-item-type="<?=(($item['item_id'] == 4074) ? 'underwear-t' : 'underwear-b')?>" data-item-id="<?=$item['item_id']?>" data-item-layer="<?=(($item['item_id'] == 4074) ? '5' : '4')?>" data-item-gender="<?=$item['gender']?>" href="#"><img src="/images/items/<?= $item['thumb'] ?>" width="42" height="42"></a></li>
                                <?php endforeach ?>
                            </ul>
                        </div>-->
                        <div class="col2 col2-1">
                            <h5>Top</h5>
                            <ul class="choice_select">
                                <?php foreach ($signup_options['shirts'] as $item): ?>
                                    <li><a class="equippable" title="<?=$item['name']?>" data-item-type="shirt" data-item-id="<?=$item['item_id']?>" data-item-layer="17" data-item-gender="<?=$item['gender']?>" href="#"><img src="/images/items/<?= $item['thumb'] ?>" width="42" height="42"></a></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                        <div class="col2 col2-2">
                            <h5>Bottom</h5>
                            <ul class="choice_select">
                                <?php foreach ($signup_options['pants'] as $item): ?>
                                    <li><a class="equippable" title="<?=$item['name']?>" data-item-type="pants" data-item-id="<?=$item['item_id']?>" data-item-layer="12" data-item-gender="<?=$item['gender']?>" href="#"><img src="/images/items/<?= $item['thumb'] ?>" width="42" height="42"></a></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                    <div class="eq-row">
                        <div class="col2 col2-1">
                            <h5>Shoes</h5>
                            <ul class="choice_select">
                                <?php foreach ($signup_options['shoes'] as $item): ?>
                                    <li><a class="equippable" title="<?=$item['name']?>" data-item-type="shoes" data-item-id="<?=$item['item_id']?>" data-item-layer="6" data-item-gender="<?=$item['gender']?>" href="#"><img src="/images/items/<?= $item['thumb'] ?>" width="42" height="42"></a></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <small class="help-block">This is just a kickstart! You will discover later a million ways to customize your avatar!</small>
            </div>
            <div class="clearfix"></div>
        </div>
        <form id="userinfo" class="form-horizontal" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>" style="margin:20px 15px 0 30px;">
            <div id="main-form" style="display: none;">
                <div style="margin-bottom: 6px;">
                    <small class="help-block text-center">Ok! Now it's time to fill in some information.</small>
                </div>
                <?php if ($this->input->get('r')): ?>
                    <input type="hidden" value="<?= $this->input->get('r') ?>" name="redirect" />
                <?php endif; ?>
                <div class="control-group">
                    <label class="control-label" for="signup-username">Username</label>
                    <div class="controls"><input type="text" id="signup-username" name="username" value="<?= set_value('username'); ?>" /><small class="help-block">Letters, numbers and spaces only.</small></div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="signup-email">Your email</label>
                    <div class="controls"><input type="text" id="signup-email" name="email" value="<?= set_value('email'); ?>" /><small class="help-block">Just in case you forget something.</small></div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="signup-password">Password</label>
                    <div class="controls"><input type="password" id="signup-password" name="password" /><small class="help-block"></small></div>
                </div>
                <div class="control-group" style="display: none">
                    <label class="control-label" for="signup-repassword">Confirm password</label>
                    <div class="controls"><input type="password" id="signup-repassword" name="repassword" /><small class="help-block">Retype your password</small></div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="signup-birthday">Birthday</label>
                    <div class="controls" class="signup-birthday" id="signup-birthday">
                        <select name="bdMonth" style="width: 108px">
                            <option value="">Month</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                        <select name="bdDay" class="signup-birthday" style="width: 60px">
                            <option value="">Day</option>
                            <?php for($day = 1; $day <= 31; $day++): ?>
                            <option value="<?=$day?>"><?=$day?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="bdYear" class="signup-birthday" style="width: 70px">
                            <option>Year</option>
                            <?php for($maxYear = $year = (intval(date('Y')) - 11); $year > ($maxYear - 80); $year--): ?>
                            <option value="<?=$year?>"><?=$year?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="signup-referal">Referal</label>
                    <div class="controls"><input type="text" id="signup-referal" name="referal" /><small class="help-block">If someone invited you here, type here his/her username</small></div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="recaptcha">Bot prevention</label>
                    <div class="controls"><?=$this->recaptcha->getWidget()?></div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <small class="help-block" style="margin-top:10px">By signing up, you agree to our <a href="/general/tos" target="_blank">Terms of Service</a> and <a href="/general/privacy" target="_blank">Private Policy</a></small>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <div class="controls text-right">
                    <button type="button" id="btn-back" class="main_button" style="display: none">Back</button>
                    <button type="button" id="btn-next" class="main_button">Next&nbsp;&rsaquo;</button>
                    <button type="submit" id="btn-submit" class="main_button" style="display: none">Finish</button>
                </div>
            </div>
        </form>
        <div class="clearfix"></div>
    </div>
</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
