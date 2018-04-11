<?php

require 'header.php';

// incloem la classe friends
include('class/friends.php');

?>

<?php if ($userLoggedIn) { // Si l'usuari ha fet login mostrem el panell d'usuari 
    $friendsObj = new Friends($dbConnection,$_SESSION['id']); ?>
    
    <div id="my-friends" class="container top40 bottom80">

        <div class="row">
            <div class="col-sm-4 col-xs-12">
              <?php include 'user-panel.php'; ?>
            </div>
            <div class="col-sm-8 col-xs-12">
              <div class="userPanelSectionBox">
                  
                <h1>Els meus amics</h1>                 
                <div id="els-meus-amics-aceptats-list">
                    
                    <h2>Acceptats</h2>                   
                    <ul class="list-group" id="group_accepted">                        
                        <?php                         
                        $userAcceptedFriends = $friendsObj->getUserAcceptedFriends();
                        foreach($userAcceptedFriends as $friend){ ?>
                            <li class="list-group-item" id="accept<?php echo "{$friend['friendId']}";?>">
                                <img src="<?php echo "{$friend['imagen']}";?>" height="50" width="50">                                 
                                <?php echo "{$friend['nom']}";?>  
                                 <div class="pull-right action-buttons">                                                                        
                                    <button class="btn btn-envelope" type="button" onclick="window.location.href='/musicalitza/my-chat.php?friendId=<?php echo "{$friend['friendId']}";?>'">
                                        <i class="glyphicon glyphicon-envelope"></i>
                                    </button>
                                    <button class="btn btn-lock" type="button" onclick="bloquejarAcceptat('<?php echo "{$userLoggedIn}";?>','<?php echo "{$friend['friendId']}";?>','<?php echo "{$friend['imagen']}";?>','<?php echo "{$friend['nom']}";?>')">
                                        <i class="glyphicon glyphicon-lock"></i>
                                    </button>
                                    <button class="btn btn-trash" type="button" onclick="esborrar('<?php echo "{$userLoggedIn}";?>','<?php echo "{$friend['friendId']}";?>','accept')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button> 
                                </div>
                            </li>                                                                                  
                        <?php } ?>                           
                    </ul>                    
                </div>
                
                <div id="els-meus-amics-pendents-acceptar-list">
                    <h2>Sol.licituds rebudes pendents</h2>                    
                    <ul class="list-group" id="group_pendents">                        
                        <?php                         
                        $userNotAcceptedFriends = $friendsObj->getUserNotAcceptedFriends();                        
                        foreach($userNotAcceptedFriends as $friend){ ?>
                            <li class="list-group-item" id="pendent<?php echo "{$friend['friendId']}";?>">
                                 <img src="<?php echo "{$friend['imagen']}";?>" height="50" width="50"> 
                                <?php echo "{$friend['nom']}";?>
                                 <div class="pull-right action-buttons">
                                    <button class="btn btn-ok" type="button" onclick="acceptarPendent('<?php echo "{$userLoggedIn}";?>','<?php echo "{$friend['friendId']}";?>','<?php echo "{$friend['imagen']}";?>','<?php echo "{$friend['nom']}";?>')">
                                        <i class="glyphicon glyphicon-ok"></i>
                                    </button>                                    
                                    <button class="btn btn-trash" type="button" onclick="esborrar('<?php echo "{$userLoggedIn}";?>','<?php echo "{$friend['friendId']}";?>','pendent')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                </div>
                            </li>                                                                                   
                        <?php } ?>                           
                    </ul>
                </div>
                
                <div id="els-meus-amics-bloquejats-list">
                    <h2>Bloquejats</h2>                    
                    <ul class="list-group" id="group_bloquejats">                        
                        <?php                         
                        $userBlockedFriends = $friendsObj->getUserBlockedFriends();
                        foreach($userBlockedFriends as $friend){ ?>
                            <li class="list-group-item" id="bloquejat<?php echo "{$friend['friendId']}";?>">
                                 <img src="<?php echo "{$friend['imagen']}";?>" height="50" width="50"> 
                                <?php echo "{$friend['nom']}";?>
                                 <div class="pull-right action-buttons">
                                    <button class="btn btn-ok" type="button" onclick="acceptarBloquejat('<?php echo "{$userLoggedIn}";?>','<?php echo "{$friend['friendId']}";?>','<?php echo "{$friend['imagen']}";?>','<?php echo "{$friend['nom']}";?>')">
                                        <i class="glyphicon glyphicon-ok"></i>
                                    </button>                                    
                                    <button class="btn btn-trash" type="button" onclick="esborrar('<?php echo "{$userLoggedIn}";?>','<?php echo "{$friend['friendId']}";?>','bloquejat')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                </div>
                            </li>                                                                                   
                        <?php } ?>                           
                    </ul>                    
                </div>
                <p>----------------------------------------------------------------</p>
                <div id="peticions-enviades-per-ser-amics-list">
                    <h2>Sol.licituds enviades pendents</h2>                    
                    <ul class="list-group" id="group_pendents-enviats">                        
                        <?php                         
                        $friendsNotAcceptedUser = $friendsObj->getFriendsNotAcceptedUser();                        
                        foreach($friendsNotAcceptedUser as $friend){ ?>
                            <li class="list-group-item" id="pendentEnviat<?php echo "{$friend['friendId']}";?>">
                                 <img src="<?php echo "{$friend['imagen']}";?>" height="50" width="50"> 
                                <?php echo "{$friend['nom']}";?>
                                 <div class="pull-right action-buttons">
                                    <button class="btn btn-trash" type="button" onclick="esborrar('<?php echo "{$userLoggedIn}";?>','<?php echo "{$friend['friendId']}";?>','pendentEnviat')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                </div>
                            </li>                                                                                   
                        <?php } ?>                           
                    </ul>                    
                </div>
              </div>
            </div>
        </div>
    </div>

<?php } else { // Si l'usuari no està identificat, mostrem error 

  include 'error.php';

} 

require 'footer.php';
?>  
