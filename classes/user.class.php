<?php
class User
{
    //perm_id 	id 	username 	password 	email 	pic_path 	pic_path_z 	state 	killed 	feed 	kills 	killed_by 	oz_opt 	fname 	lname 	starved 	lifetime_kills 	games_completed 	active

    protected $protected = 'Protected';
    private $private = 'Private';

    function printHello()
    {
        echo $this->public;
        echo $this->protected;
        echo $this->private;
    }
}


