<style>
.chat-part{background-color: transparent;}

.chat-part .chat-search-box {
    -webkit-border-radius: 3px 0 0 0;
    -moz-border-radius: 3px 0 0 0;
    border-radius: 3px 0 0 0;
    padding: .75rem 1rem;
}

.chat-part .chat-search-box .input-group .form-control {
    -webkit-border-radius: 2px 0 0 2px;
    -moz-border-radius: 2px 0 0 2px;
    border-radius: 2px 0 0 2px;
    border-right: 0;
}

.chat-part .chat-search-box .input-group .form-control:focus {
    border-right: 0;
}

.chat-part .chat-search-box .input-group .input-group-btn .btn {
    -webkit-border-radius: 0 2px 2px 0;
    -moz-border-radius: 0 2px 2px 0;
    border-radius: 0 2px 2px 0;
    margin: 0;
}

.chat-part .chat-search-box .input-group .input-group-btn .btn i {
    font-size: 1.2rem;
    line-height: 100%;
    vertical-align: middle;
}

@media (max-width: 767px) {
    .chat-part .chat-search-box {
        display: none;
    }
	.chat-part .form-group.mt-3.mb-0 {
		position: fixed;
		bottom: 0px;
		left: 0;
		right: 0;
	}
	.chat-part .col-xl-8.col-lg-8.col-md-8.col-sm-9.col-9 {
		margin-bottom: 50px;
	}
}

.chat-part .users-container {
    position: relative;
    padding: 1rem 0;
    border-right: 1px solid #e6ecf3;
    height: 100%;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
}

.chat-part .users {
    padding: 0;
}

.chat-part .users .person {
    position: relative;
    width: 100%;
    padding: 10px 1rem;
    cursor: pointer;
    border-bottom: 1px solid #f0f4f8;
}

.chat-part button.btn.btn-info {
    background: #303d48;
}

.chat-part .users .person:hover {
    background-color: #ffffff;
    /* Fallback Color */
    background-image: -webkit-gradient(linear, left top, left bottom, from(#e9eff5), to(#ffffff));
    /* Saf4+, Chrome */
    background-image: -webkit-linear-gradient(right, #e9eff5, #ffffff);
    /* Chrome 10+, Saf5.1+, iOS 5+ */
    background-image: -moz-linear-gradient(right, #e9eff5, #ffffff);
    /* FF3.6 */
    background-image: -ms-linear-gradient(right, #e9eff5, #ffffff);
    /* IE10 */
    background-image: -o-linear-gradient(right, #e9eff5, #ffffff);
    /* Opera 11.10+ */
    background-image: linear-gradient(right, #e9eff5, #ffffff);
}

.chat-part .users .person.active-user {
    background-color: #ffffff;
    /* Fallback Color */
    background-image: -webkit-gradient(linear, left top, left bottom, from(#f7f9fb), to(#ffffff));
    /* Saf4+, Chrome */
    background-image: -webkit-linear-gradient(right, #f7f9fb, #ffffff);
    /* Chrome 10+, Saf5.1+, iOS 5+ */
    background-image: -moz-linear-gradient(right, #f7f9fb, #ffffff);
    /* FF3.6 */
    background-image: -ms-linear-gradient(right, #f7f9fb, #ffffff);
    /* IE10 */
    background-image: -o-linear-gradient(right, #f7f9fb, #ffffff);
    /* Opera 11.10+ */
    background-image: linear-gradient(right, #f7f9fb, #ffffff);
}

.chat-part .users .person:last-child {
    border-bottom: 0;
}

.chat-part .users .person .user {
    display: inline-block;
    position: relative;
    margin-right: 10px;
}

.chat-part .users .person .user img {
    width: 48px;
    height: 48px;
    -webkit-border-radius: 50px;
    -moz-border-radius: 50px;
    border-radius: 50px;
}

.chat-part .users .person .user .status {
    width: 10px;
    height: 10px;
    -webkit-border-radius: 100px;
    -moz-border-radius: 100px;
    border-radius: 100px;
    background: #e6ecf3;
    position: absolute;
    top: 0;
    right: 0;
}

.chat-part .users .person .user .status.online {
    background: #9ec94a;
}

.chat-part .users .person .user .status.offline {
    background: #c4d2e2;
}

.chat-part .users .person .user .status.away {
    background: #f9be52;
}

.chat-part .users .person .user .status.busy {
    background: #fd7274;
}

.chat-part .users .person p.name-time {
    font-weight: 600;
    font-size: .85rem;
    display: inline-block;
}

.chat-part .users .person p.name-time .time {
    font-weight: 400;
    font-size: .7rem;
    text-align: right;
    color: #8796af;
}

@media (max-width: 767px) {
    .chat-part .users .person .user img {
        width: 30px;
        height: 30px;
    }
    .chat-part .users .person p.name-time {
        display: none;
    }
    .chat-part .users .person p.name-time .time {
        display: none;
    }
}

.chat-part .selected-user {
    width: 100%;
    padding: 0 15px;
    padding-top: 5vmin;
    min-height: 64px;
    line-height: 64px;
    border-bottom: 1px solid #e6ecf3;
    -webkit-border-radius: 0 3px 0 0;
    -moz-border-radius: 0 3px 0 0;
    border-radius: 0 3px 0 0;
}

.chat-part .selected-user span {
    line-height: 100%;
}

.chat-part .selected-user span.name {
    font-weight: 700;
}

.chat-part .chat-container {
    position: relative;
    padding: 1rem;
}

.chat-part .chat-container li.chat-left,
.chat-part .chat-container li.chat-right {
    display: flex;
    flex: 1;
    flex-direction: row;
    margin-bottom: 40px;
}

.chat-part .chat-container li img {
    width: 48px;
    height: 48px;
    -webkit-border-radius: 30px;
    -moz-border-radius: 30px;
    border-radius: 30px;
}

.chat-part .chat-container li .chat-avatar {
    margin-right: 20px;
}

.chat-part .chat-container li.chat-right {
    justify-content: flex-end;
}

.chat-part .chat-container li.chat-right > .chat-avatar {
    margin-left: 20px;
    margin-right: 0;
}

.chat-part .chat-container li .chat-name {
    font-size: .75rem;
    color: #999999;
    text-align: center;
}

.chat-part .chat-container li .chat-text {
    padding: .4rem 1rem;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    background: #fffd;
    font-weight: 300;
    line-height: 150%;
    position: relative;
}

.chat-part .chat-container li .chat-text:before {
    content: '';
    position: absolute;
    width: 0;
    height: 0;
    top: 10px;
    left: -20px;
    border: 10px solid;
    border-color: transparent #ffffff transparent transparent;
}
@media (max-width: 470px){
	.chat-part .chat-container li .chat-text:before {
		display: none;
	}
}

.chat-part .chat-container li.chat-right > .chat-text {
    text-align: right;
}

.chat-part .chat-container li.chat-right > .chat-text:before {
    right: -20px;
    border-color: transparent transparent transparent #ffffff;
    left: inherit;
}

.chat-part .chat-container li .chat-hour {
    padding: 0;
    margin-bottom: 10px;
    font-size: .75rem;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    margin: 0 0 0 15px;
}

.chat-part .chat-container li .chat-hour > span {
    font-size: 16px;
    color: #9ec94a;
}

.chat-part .chat-container li.chat-right > .chat-hour {
    margin: 0 15px 0 0;
}

@media (max-width: 767px) {
    .chat-container li.chat-left,
    .chat-container li.chat-right {
        flex-direction: column;
        margin-bottom: 30px;
    }
    .chat-part .chat-container li img {
        width: 32px;
        height: 32px;
    }
    .chat-part .chat-container li.chat-left .chat-avatar {
        margin: 0 0 5px 0;
        display: flex;
        align-items: center;
    }
    .chat-part .chat-container li.chat-left .chat-hour {
        justify-content: flex-end;
    }
    .chat-part .chat-container li.chat-left .chat-name {
        margin-left: 5px;
    }
    .chat-part .chat-container li.chat-right .chat-avatar {
        order: -1;
        margin: 0 0 5px 0;
        align-items: center;
        display: flex;
        justify-content: right;
        flex-direction: row-reverse;
    }
    .chat-part .chat-container li.chat-right .chat-hour {
        justify-content: flex-start;
        order: 2;
    }
    .chat-part .chat-container li.chat-right .chat-name {
        margin-right: 5px;
    }
    .chat-part .chat-container li .chat-text {
        font-size: .8rem;
    }
}

.chat-part .chat-form {
    padding: 15px;
    width: 100%;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ffffff;
    border-top: 1px solid white;
}

.chat-part ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}
.chat-part .card {
    border: 0;
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 2px;
    margin-bottom: 2rem;
	background-color: transparent;
    box-shadow: none;
}
</style>
<div class="container-fluid chat-part">

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">

            <div class="col-md">

                <div class="card m-0">

                    <!-- Row start -->
                    <div class="row no-gutters">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-3 col-3">
                            <div class="users-container">
                                <div class="chat-search-box">
                                    <div class="input-group">
                                        <input class="form-control" placeholder="Search">
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-info">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <ul class="users">
                                    <li class="person" data-chat="person1">
                                        <div class="user">
                                            <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
                                            <span class="status busy"></span>
                                        </div>
                                        <p class="name-time">
                                            <span class="name">Steve Bangalter</span>
                                            <span class="time">15/02/2019</span>
                                        </p>
                                    </li>
                                    <li class="person" data-chat="person1">
                                        <div class="user">
                                            <img src="https://www.bootdey.com/img/Content/avatar/avatar1.png" alt="Retail Admin">
                                            <span class="status offline"></span>
                                        </div>
                                        <p class="name-time">
                                            <span class="name">Steve Bangalter</span>
                                            <span class="time">15/02/2019</span>
                                        </p>
                                    </li>
                                    <li class="person active-user" data-chat="person2">
                                        <div class="user">
                                            <img src="https://www.bootdey.com/img/Content/avatar/avatar2.png" alt="Retail Admin">
                                            <span class="status away"></span>
                                        </div>
                                        <p class="name-time">
                                            <span class="name">Peter Gregor</span>
                                            <span class="time">12/02/2019</span>
                                        </p>
                                    </li>
                                    <li class="person" data-chat="person3">
                                        <div class="user">
                                            <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
                                            <span class="status busy"></span>
                                        </div>
                                        <p class="name-time">
                                            <span class="name">Jessica Larson</span>
                                            <span class="time">11/02/2019</span>
                                        </p>
                                    </li>
                                    <li class="person" data-chat="person4">
                                        <div class="user">
                                            <img src="https://www.bootdey.com/img/Content/avatar/avatar4.png" alt="Retail Admin">
                                            <span class="status offline"></span>
                                        </div>
                                        <p class="name-time">
                                            <span class="name">Lisa Guerrero</span>
                                            <span class="time">08/02/2019</span>
                                        </p>
                                    </li>
                                    <li class="person" data-chat="person5">
                                        <div class="user">
                                            <img src="https://www.bootdey.com/img/Content/avatar/avatar5.png" alt="Retail Admin">
                                            <span class="status away"></span>
                                        </div>
                                        <p class="name-time">
                                            <span class="name">Michael Jordan</span>
                                            <span class="time">05/02/2019</span>
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-9 col-9">
                            <div class="selected-user">
                                <span>To: <span class="name">Emily Russell</span></span>
                            </div>
                            <div class="chat-container">
                                <ul class="chat-box chatContainerScroll">
                                    <li class="chat-left row">
                                        <div class="chat-avatar">
                                            <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
                                            <div class="chat-name">Russell</div>
                                        </div>
                                        <div class="chat-text col-sm">Hello, I'm Russell.
                                            <br>How can I help you today?</div>
                                        <div class="chat-hour">08:55 <span class="fa fa-check-circle"></span></div>
                                    </li>
                                    <li class="chat-right row">
                                        <div class="chat-hour">08:56 <span class="fa fa-check-circle"></span></div>
                                        <div class="chat-text col-sm">Hi, Russell
                                            <br> I need more information about Developer Plan.</div>
                                        <div class="chat-avatar">
                                            <img src="/test/avatar.png" alt="Retail Admin">
                                            <div class="chat-name">Jin</div>
                                        </div>
                                    </li>
                                    <li class="chat-left row">
                                        <div class="chat-avatar">
                                            <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
                                            <div class="chat-name">Russell</div>
                                        </div>
                                        <div class="chat-text col-sm">Are we meeting today?
                                            <br>Project has been already finished and I have results to show you.</div>
                                        <div class="chat-hour">08:57 <span class="fa fa-check-circle"></span></div>
                                    </li>
                                    <li class="chat-right row">
                                        <div class="chat-hour">08:59 <span class="fa fa-check-circle"></span></div>
                                        <div class="chat-text col-sm">Well I am not sure.
                                            <br>I have results to show you.</div>
                                        <div class="chat-avatar">
                                            <img src="/test/avatar.png" alt="Retail Admin">
                                            <div class="chat-name">Jin</div>
                                        </div>
                                    </li>
                                    <li class="chat-left row">
                                        <div class="chat-avatar">
                                            <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
                                            <div class="chat-name">Russell</div>
                                        </div>
                                        <div class="chat-text col-sm">The rest of the team is not here yet.
                                            <br>Maybe in an hour or so?</div>
                                        <div class="chat-hour">08:57 <span class="fa fa-check-circle"></span></div>
                                    </li>
                                    <li class="chat-right row">
                                        <div class="chat-hour">08:59 <span class="fa fa-check-circle"></span></div>
                                        <div class="chat-text col-sm">Have you faced any problems at the last phase of the project?</div>
                                        <div class="chat-avatar">
                                            <img src="/test/avatar.png" alt="Retail Admin">
                                            <div class="chat-name">Jin</div>
                                        </div>
                                    </li>
                                    <li class="chat-left row">
                                        <div class="chat-avatar">
                                            <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
                                            <div class="chat-name">Russell</div>
                                        </div>
                                        <div class="chat-text col-sm">Actually everything was fine.
                                            <br>I'm very excited to show this to our team.</div>
                                        <div class="chat-hour">07:00 <span class="fa fa-check-circle"></span></div>
                                    </li>
                                </ul>
                                <div class="form-group mt-3 mb-0">
                                    <textarea class="form-control" rows="3" placeholder="Type your message here..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Row end -->
                </div>

            </div>

        </div>
        <!-- Row end -->

    </div>
    <!-- Content wrapper end -->

</div>