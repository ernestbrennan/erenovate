<?php

return [
    'invite_new' => '
        :contractor_name of <strong>:contractor_business_name</strong> invited you to join the project :project_name, on the eRenovate Guarantee Platform.              <br>
                                                                                                                                                       <br>
        This ensures your project is protected by the eRenovate Guarantee, and you get Peace of Mind.                                                  <br>
                                                                                                                                                       <br>
        Please, sign in using the temporary password below;                                                                                            <br>
                                                                                                                                                       <br>
        Your username: :homeowner_email                                                                                                                <br>       
        Your password: :homeowner_password                                                                                                             <br>
                                                                                                                                                       <br>
        Please, <a href=" ' . route('sign_in') . '">log in here</a>                                                                             <br>
                                                                                                                                                       <br>
        If you have any questions, eRenovate is here to lend a hand. Feel free to <a href="https://www.erenovate.com/homeowners/contact">Contact Us</a> anytime.    <br>
                                                                                                                                                       <br>
        Happy eRenovating,                                                                                                                             <br>
                                                                                                                                                       <br>
        Team eRenovate',

    'invite_old' => ' 
        :contractor_name of <strong>:contractor_business_name</strong> invited you to join the project :project_name, on the eRenovate Guarantee Platform.               <br>
                                                                                                                                                        <br>
        This ensures your project is protected by the eRenovate Guarantee, and you get Peace of Mind.                                                   <br>
                                                                                                                                                        <br>
        Please, <a href=" ' . route('sign_in') . '">log in here</a>                                                                                <br>
                                                                                                                                                        <br>
        If you have any questions, eRenovate is here to lend a hand. Feel free to <a href="https://www.erenovate.com/homeowners/contact">Contact Us</a> anytime.     <br>
                                                                                                                                                        <br>
        Happy eRenovating,                                                                                                                              <br>
                                                                                                                                                        <br>
        Team eRenovate',
];

