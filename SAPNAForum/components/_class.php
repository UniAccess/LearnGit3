<?php
   session_start();
   
    $con = mysql_connect("localhost","root","");
	mysql_select_db("sapnaforum",$con) or die("cannot find database");
   
   class Forum
   {
	   var $forumName;
	   var $host;	
	   var $user;
	   var $pass;
	   var $dbname;
	   var $db;
	   var $result;
	   
	   function __construct($host="",$user="",$pass="" ,$dbname="" )
	   {
		   $this->host = $host;
		   $this->user = $user;
		   $this->pass = $pass;
		   $this->dbname = $dbname;
		   if( $this->host == " " || $this->user == " " ||$this->pass == " " ||$this->dbname)
		   {}else $this->db = new MySQLi($this->host,$this->user,$this->pass,$this->dbname) or die("Could not connect");
		   
	   }//constructor ends
	   
	   function Page($pagename)
	   {
		   ?>
           <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
           <html xmlns="http://www.w3.org/1999/xhtml">
           <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <link href="css/main.css" rel="stylesheet" type="text/css" />
                <script src="js/jquery.js" type="text/javascript"></script>
                <script src="js/main.js" type="text/javascript"></script>                
                <title><?php echo "Sapna Forum | ".ucfirst($pagename);?></title>
           </head>

           <body>
              <div id="body">
              <div id="wrapper">
              <div id="header"">
                  <img src="images/logo.png" alt="SAPNA Forum" />
                  
                  <h2 style="width:800px;float:left;margin-top:12px;margin-left:160px;">Where even silly questions are welcome!</h2>
              <div id="menu">
                 <ul>
                    <li><a href="index.php">Home</a></li>
                    
                    
                    <?php 
					   if(isset($_SESSION['Username']))echo '<li><a href="logout.php">Logout</a></li>';
					   else
					   {
						   echo '<li><a href="login.php">Login</a></li>';
						   echo '<li><a href="register.php">Register</a></li>';
						}
					?>
                 </ul>
              </div>
              </div>
              
              
              <div id="cbody">
              <hr />
              <br/>
              <?php
			  //selecting page here
			  switch(strtoupper($pagename)) 
			  {
				  case "HOME": $this->HomePage();
				               break;
                  case "LOGIN": $this->LoginPage();
				               break;
				  case "REGISTER": $this->RegisterPage(); 
				                  break; 
				  case "POST": $this->specificposts();
				               break;
							   
			  }
			  ?>
                 </div>
                 <div id="footer">&nbsp;</div>
              </div>
              </div>
           </body>
           </html>
           <?php
	   }//page layout ends
	   
	   function HomePage()
	   {
		   ?>
		   
               <?php
			      if(isset($_SESSION['Username'])){
					     $_SESSION['postingallows']=true;
				      ?>
                      <div id="left">   
					     <div id="postarea">
                             <img src="<?php echo Forum::getpicurl(Forum::getuser($_SESSION['Username']));?>" alt="<?php echo $_SESSION['Username'];?>" width:100px" height="100px"/>
                             <div> 
                             <form method="post" action="post.php">
                                <input type="text" name="title" id="title"/>
                                <textarea name="post" id="post" cols="20" rows="4"></textarea>
                                <input type="submit" value="Ask Question" />
                             </form>
                             </div>
                         </div>
					  
				
           </div>
           <?php
	              }
           ?>
           <div id="right">
              <?php 
			     $sql="select * from posts order by Date desc";
				 $res=mysql_query($sql);
				 while($k = mysql_fetch_assoc($res))
				 {
					 ?>
                         <hr  /><br />
					    <div class="1post">

                           <img src="<?php echo Forum::getpicurl($k['bywho']);?>" width="50px" height="50px" alt="" />
                           <div class="1postcontent">
                              <p><b><?php echo $k['title'];?></b>   <i>By <?php echo Forum::getusername($k['bywho']); ?></i></p>
                              <p><?php echo $k['content'];?></p>
                              <p class="numreplies"><?php echo Forum::getnumreplies($k['postId']); ?></p>
                              <a href="posts.php?where=<?php echo $k['postId']?>" class="seemore">Read more</a>
                           </div>
                           <?php  
						       $sqlmax = "select MAX(responseId) as maxr from response where postId=".$k['postId']."";
							   $res8=mysql_query($sqlmax) or die("max doesn't work");
							   $max=1;
							   //die($res);
							   while($g = mysql_fetch_assoc($res8))$max = $g['maxr'];
							   //die("dfsdfsdfsdfsd ".$max . " " . $res);
							   //die($max);
							   if(empty($max)|| $max == 0)
							   {
							   }else
							   {
							   $sql2="select * from response where postId=".$k['postId']." and responseId=".$max;
							   //die($sql2. $max);
							   //echo $sql2;
							   $res5 = mysql_query($sql2);
							   
							   if(mysql_num_rows($res5)){
								  $res7 = mysql_query($sql2);
								  while($b = mysql_fetch_assoc($res7)){
									  

								  
						   ?>
                           <div class="replies">
                               <img src="<?php echo Forum::getpicurl($b['userId']);?>" width="45px" height="45px" alt=""/>
                               <div class="replies1">
                                  <p><i>By <?php echo Forum::getusername($b['userId']);?></i></p>
                                  <p><?php echo $b['response'];?></p>
                               </div>
                           </div>
                           <?php 
								  }
							    }
							   }
						   ?>
                        </div>
					 <?php
					 if(isset($_SESSION['Username'])){
					    //give option to respond
						?>
                        <div class="response">
                            <img src="" alt="" />
                            <div class="response1">
                            <form method="post" action="response.php">
                                <textarea name="respost" cols="20" rows="4"></textarea>
                                <input type="hidden" value="<?php echo $k['postId'];?>" name="tthis"  />
                                <input type="submit" value="Reply" />
                             </form>
                             </div>
                        </div>
                        <?php	 
					 }
				 }
			  ?>
           </div>
           <?php
	   }
	   
	   function specificposts()
	   {
		   if(isset($_GET['where']) && !empty($_GET['where']) && Is_Numeric($_GET['where']) )
		   { ?>
             <div id="right">
              <?php 
			     $sql="select * from posts where postId=".$_GET['where'];
				 $res=mysql_query($sql);
				 while($k = mysql_fetch_assoc($res))
				 {
					 ?>
					    <div class="1post">

                           <img src="<?php echo Forum::getpicurl($k['bywho']);?>" width="50px" height="50px" alt="" />
                           <div class="1postcontent">
                              <p><b><?php echo $k['title'];?></b>   <i>By <?php echo Forum::getusername($k['bywho']); ?></i></p>
                              <p><?php echo $k['content'];?></p>
                              <p class="numreplies"><?php echo Forum::getnumreplies($k['postId']); ?></p>
                              <a href="posts.php?where=<?php echo $k['postId']?>" class="seemore">See more</a>
                           </div>
                           <?php  
						       
							   //die("dfsdfsdfsdfsd ".$max . " " . $res);
							   $sql2="select * from response where postId=".$k['postId'];
							   //echo $sql2;
							   $res5 = mysql_query($sql2);
							   
							   if(mysql_num_rows($res5)){
								  $res5 = mysql_query($sql2);
								  while($b = mysql_fetch_assoc($res5)){
									  
								      
								  
						   ?>
                           
                           <div class="replies">
                               <img src="<?php echo Forum::getpicurl($b['userId']);?>" width="45px" height="45px" alt=""/>
                               <div class="replies1">
                                  <p><i>By <?php echo Forum::getusername($b['userId']);?></i></p>
                                  <p><?php echo $b['response'];?></p>
                               </div>
                           </div>
                           <?php 
								  }
							   }
						   ?>
                        </div>
					 <?php
					 if(isset($_SESSION['Username'])){
					    //give option to respond
						?>
                        <div class="response">
                            <img src="" alt="" />
                            <div class="response1">
                            <form method="post" action="response.php?where=<?php echo $_GET['where'];?>">
                                <textarea name="respost" cols="20" rows="4"></textarea>
                                <input type="hidden" value="<?php echo $k['postId'];?>" name="tthis"  />
                                <input type="submit" value="Reply" />
                             </form>
                             </div>
                        </div>
                        <?php	 
					 }
				 }
			  ?>
           </div>
<?php
		   }
		   else
		   {
			   header('Location: index.php');
			   
		   }
	   }
	   function getnumreplies($postid)
	   {
		   $sql="select * from response where postId=".$postid;
		   $var = mysql_num_rows(mysql_query($sql));
		   if( $var == 0)return "";
		   else if($var == 1)return $var." reply";
		   else return $var." replies";
	   }
	   //utility function get user
	   function getuser($username)
	   {
		   $sql = "select userId from susers where userName='".$username."'";
		   $res = mysql_query($sql);
		   $id=23;
		   while($b = mysql_fetch_assoc($res)){$id=$b['userId'];break;}
		   return $id;
       }
	   
	   //utility fucntion to get userprofile pic
	   function getpicurl($userid)
	   {
		   $sql = "select profilePic from susers where userId='".$userid."'";
		   $res = mysql_query($sql);
		   $url="";
		   while($b = mysql_fetch_assoc($res)){$url=$b['profilePic'];break;}
		   if($url == null)$url = "media/default.jpg";
//		   echo $url;
		   return $url;
	   }
	   
	   function getusername($userid)
	   {
		   $sql = "select userName from susers where userId='".$userid."'";
		   $res = mysql_query($sql);
		   $id="";
		   while($b = mysql_fetch_assoc($res)){$id=$b['userName'];break;}
		   return $id;
	   }
	   
	   function ProtectString($string)
	   {
		   return mysql_real_escape_string(htmlspecialchars(trim($string)));
	   }
           
       function LoginPage()
       {
          
			   ?>
           <div id="logbody" >
               <h2>Login</h2>
               <form action="alogin.php" method="post">
               <table>
                   <tr>
                       <td><?php if(isset($_SESSION['corlog']))if($_SESSION['corlog'] == false)echo "<p>Incorrect Login</p>";  ?></td>
                   </tr>
                   <tr>
                       <td>* Username:</td>
                       <td><input type="text" name="user" /> </td>
                   </tr>
                   <tr>
                       <td>* Password:</td>
                       <td><input type="password" name="pass" /> </td>
                   </tr>
                   <tr>
                      <td colspan="2">
                          <input type="submit" value="Log In" /><input type="reset" value="Reset" />
                      </td>
                   </tr>
                   <tr>
                      <td colspan="2">
                          <input type="checkbox" name="remember" />Remember Me
                      </td>
                   </tr>
                   <tr>
                      <td colspan="2">
                          <a href="register.php" class="">Not a user, register now</a>
                      </td>
                   </tr>
               </table>
               </form>
           </div>
               <?php
               
        }//LOGIN PAGE ENDS
		
		function CheckLogin()
		{
 			  $web = new Forum("localhost","root","","sapnaforum");
			 if(isset($_POST['user']) && isset($_POST['pass'])){
				 $user = Forum::ProtectString($_POST['user']);
				 $pass = Forum::ProtectString($_POST['pass']);
				
				 echo $pass;
				 if($user =="" || $pass==""){
					  $_SESSION['corlog']=false;
					  header('Location: login.php');
					  
					  //return;
			     }else{
					   if(isset($_POST['remember'])){
				              $_SESSION['Username'] =$user;
							  setcookie("Username",$user,time()+3600);
				       }
				      $sql="select * from susers where userName='".$user."' and password='".crypt($pass,'college')."'";

					  
                        $con = mysql_connect("localhost","root","");
                    	mysql_select_db("sapnaforum",$con) or die("cannot find database");
					   $result = mysql_query($sql,$con) or die("failed query");
					   //$res = mysql_query($sql,$con); 
					   //echo $res = $web->db;// ->db->query($sql,MYSQLI_USE_RESULT) or die("failed query");
                       
					  if(mysql_num_rows($result) >0)
					  {
					      $_SESSION['corlog']=true;	 
						  $_SESSION['Username'] =$user;
						  header('Location: index.php');
					  }
					  else
					  {
						 $_SESSION['corlog']=false;	 
						  //$_SESSION['Username'] =$user;
						  header('Location: login.php'); 
					  }
				 }
				 
		     }
			 else $_SESSION['corlog']=false;
		}
		
		function RegisterPage()
		{
			?>
			<div id="logbody">
               <h2>Register</h2>
               <form action="areg.php" method="post" enctype="multipart/form-data">
               <table>
                   <tr>
                       <td colspan="2"><?php if(isset($_SESSION['correg']))if($_SESSION['correg'] == false)echo "<p>All Fields Required</p>";else echo "<p>Profile Created Successfully</p>";  ?></td>
                   </tr>
                   <tr>
                       <td colspan="2"><?php if(isset($_SESSION['imgerr']))if($_SESSION['imgerr'] == false)echo "<p>Please upload a JPEG, PNG or GIF Image</p>";?></td>
                   </tr>
                   <tr>
                       <td colspan="2"><?php if(isset($_SESSION['imgup']))if($_SESSION['imgup'] == false)echo "<p>File Upload error, please try again</p>";?></td>
                   </tr>
                   <tr>
                       <td>* First Name:</td>
                       <td><input type="text" name="fname" /> </td>
                   </tr>
                   <tr>
                       <td>* Last Name:</td>
                       <td><input type="text" name="lname" /> </td>
                   </tr>
                   <tr>
                       <td>* Username:</td>
                       <td><input type="text" name="user" /> </td>
                   </tr>
                   <tr>
                       <td>* Password:</td>
                       <td><input type="password" name="pass" /> </td>
                   </tr>
                   <tr>
                     <td>* Profile Picture:</td>
                     <td><input type="file" name="image" accept="image/gif, image/jpeg,image/png"/></td>
                   </tr>
                   <tr>
                      <td colspan="2">
                          <input type="submit" value="Register" /><input type="reset" value="Reset" />
                      </td>
                   </tr>
                   
               </table>
               </form>
           </div>
           <?php
		}
		
		function CheckRegister()
		{
			   //$web = new Forum("localhost","root","","sapnaforum");
            
		    if(isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['fname']) && isset($_POST['lname'])){
				 $user = Forum::ProtectString($_POST['user']);
				 $pass = Forum::ProtectString($_POST['pass']);
				 $fname = Forum::ProtectString($_POST['fname']);
				 $lname = Forum::ProtectString($_POST['lname']);
                 $img = "media/default.jpg";
				 if(isset($_FILES['image'])){
					 if($_FILES['image']['error'] > 0)
					 {
						 $_SESSION['imgup']=false;
				     }
					 else
					 {
						 $_SESSION['imgup']=true;
						 $type=$_FILES['image']['type'];
      					 if($type == "image/gif" || $type =="image/jpeg" || $type =="image/png"){
		        		        $_SESSION['imgerr']=true;
				      		    copy($_FILES['image']['tmp_name'],'media/'.$_FILES['image']['name']);
						        $img = "media/".$_FILES['image']['name'];
					     }
					     else {
					         $_SESSION['imgerr']=false;
                             //die("Unable to add image");
						     header('Location: register.php');
					     }
				     }
					 
				 }
				 
				 
				 //if($user ==" " || $pass=" " || $fname=" " || $lname=" "){
					//  $_SESSION['correg']=false;
					//  header('Location: register.php');
					  
					//  return;
			     //}else{
				     
					  $sql="insert into susers( `firstName`, `lastName`, `userName`, `password`, `profilePic`) 
					        values('".$fname."','".$lname."','".$user."','".crypt($pass,'college')."','".$img."')";
                      $con = mysql_connect("localhost","root","");
					  mysql_select_db("sapnaforum",$con);
					  mysql_query($sql,$con);
					  //$this->db->send_query($sql) or die("failed query");
					  //if($this->db->affected_rows> 0)
					  //{
					      $_SESSION['correg']=true;	 
						  $_SESSION['Username'] =$user;
						  $_SESSION['corlog']=true;
						  header('Location: login.php');
					  //}
				 //}
				 
		     }
			 else $_SESSION['correg']=false;	
		}//check register ends
		
		function newpost()
		{
			if(isset($_SESSION['Username']))
			{
			   //process
			   if(!isset($_POST['title']) || !isset($_POST['post']))
			   {
				   header('Location: index.php');
			   }
			   else
			      {
			         $title= Forum::ProtectString($_POST['title']);
			         $post = Forum::ProtectString($_POST['post']);
					 
					 $sql3="insert into posts(`title`,`content`,`date`,`bywho`) values('".$title."','".$post."',NOW( ),".Forum::getuser($_SESSION['Username']).")";
					 mysql_query($sql3);
					 header('Location: index.php');
				  }
			}
		}//new post ends
		
		function newresponse()
		{
			if(isset($_SESSION['Username']))
			{
			   //process
			   if(!isset($_POST['respost']) && !isset($_POST['tthis']) )
			   {
				    header('Location: index.php');
			   }
			   else
			      {
			         $response= Forum::ProtectString($_POST['respost']);
                     $postid = Forum::ProtectString($_POST['tthis']);
					 
					 
					 
					 $sql3="INSERT INTO `response`( `userId`, `postId`, `response`, `Date`) VALUES (".Forum::getuser($_SESSION['Username']).",".$postid.",'".$response."',NOW( ))";

					 mysql_query($sql3);
					 if(isset($_GET['where']))header('Location: posts.php?where='.$_GET['where']);
				     else
					 header('Location: index.php');
				  }
			}
		}
		
		
   }//class ends

   
   $web = new Forum("localhost","root","","sapnaforum");
   
   
   if(isset($_COOKIE['Username']) && !isset($_SESSION['Username']))
     $_SESSION['Username']=$_COOKIE['Username'];
   
?>

