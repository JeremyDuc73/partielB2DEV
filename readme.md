<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
        <h1 class="mt-4 mb-3">Documentation partiel API</h1>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/register</u></h5>
        <h6>Type de route : POST</h6>
        <h6>Exemple de corps de requête : <br>
            <code>
                { <br>
                "email":"jeremy@mail.com", <br>
                "password":"coucou" <br>
                } <br><br>
            </code>
            le mot de passe doit faire au moins 6 caractères, cette requête créée un compte dans l'application
        </h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/login-check</u></h5>
        <h6>Type de route : POST</h6>
        <h6>Exemple de corps de requête : <br>
            <code>
                { <br>
                "email":"jeremy@mail.com", <br>
                "password":"coucou" <br>
                } <br><br>
            </code>
            Cette requête retourne un token pour connecter un utilisateur à l'application
        </h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/profiles</u></h5>
        <h6>Type de route : GET</h6>
        <h6>Cette requête retourne une liste des comptes présents sur l'application</h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/profile/{profileId}/edit</u></h5>
        <h6>Type de route : POST</h6>
        <h6>Exemple de corps de requête : <br>
            <code>
                { <br>
                "displayName":"jeremy" <br>
                } <br><br>
            </code>
            Cette requête change le nom qui est affiché sur le profil d'un utilisateur, elle nécessite l'id du profil en question dans son url
        </h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/event/create</u></h5>
        <h6>Type de route : POST</h6>
        <h6>Exemple de corps de requête : <br>
            <code>
                { <br>
                "place":"paris", <br>
                "description":"soirée publique lieu privé",<br>
                "startingDate":"2023-12-26",<br>
                "endDate":"2023-12-31",<br>
                "private":false,<br>
                "privatePlace":true <br>
                } <br><br>
            </code>
            cette requête créée un événement avec les données que vous lui donnez
        </h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/events</u></h5>
        <h6>Type de route : GET</h6>
        <h6>Cette requête retourne une liste des événements <u>publics</u> présents sur l'application</h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/events/organized</u></h5>
        <h6>Type de route : GET</h6>
        <h6>Cette requête retourne une liste des événements dont l'utilisateur en cours est l'organisateur</h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/events/participant</u></h5>
        <h6>Type de route : GET</h6>
        <h6>Cette requête retourne une liste des événements dont l'utilisateur en cours est un participant</h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/event/{eventId}/join</u></h5>
        <h6>Type de route : POST</h6>
        <h6>Cette requête permet à l'utilisateur en cours de rejoindre un événement public, il faut spécifier l'id de ce dernier dans l'url</h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/event/{eventId}/invite/{profileId}</u></h5>
        <h6>Type de route : POST</h6>
        <h6>Cette requête permet à l'utilisateur en cours s'il est l'organisateur d'un événement privé d'inviter un autre utilisateur à rejoindre son événement, il faut spécifier l'id de l'événement ainsi que l'id du profil de l'utilisateur ciblé dans l'url</h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/myinvitations</u></h5>
        <h6>Type de route : GET</h6>
        <h6>Cette requête retourne une liste des invitations que l'utilisateur en cours a reçu (qu'elles soient en attente, refusées ou acceptées)</h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/invitation/{invitationId}/accept</u></h5>
        <h6>Type de route : POST</h6>
        <h6>Cette requête permet à l'utilisateur en cours d'accepter une invitation à un événement privé et donc de le rejoindre en tant que participant, il faut spécifier l'id de l'invitation dans l'url</h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/invitation/{invitationId}/deny</u></h5>
        <h6>Type de route : POST</h6>
        <h6>Cette requête permet à l'utilisateur en cours de refuser une invitation à un événement privé, il faut spécifier l'id de l'invitation dans l'url</h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/event/{eventId}/participants</u></h5>
        <h6>Type de route : GET</h6>
        <h6>Cette requête retourne une liste des participants d'un événement. Dans le cas d'un événement public il n'y a pas de contrainte, mais dans le cas d'un événement privé il faut soit avoir été invité, être participant ou être l'organisateur</h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/event/{eventId}/changeschedule</u></h5>
        <h6>Type de route : POST</h6>
        <h6>Cette requête permet à l'utilisateur en cours, s'il est l'organisateur d'un événement, d'en changer le status (validé ou annulé), il faut passer l'id de l'événement concerné dnas l'url</h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/event/{eventId}/edit/dates</u></h5>
        <h6>Type de route : PUT</h6>
        <h6>Exemple de corps de requête : <br>
            <code>
                { <br>
                "startingDate":"2023-12-26",<br>
                "endDate":"2023-12-31"<br>
                } <br><br>
            </code>
            Cette requête permet à l'utilisateur en cours, s'il est l'organisateur d'un événement, d'en changer les dates de début et de fin (commence dans le futur et date de fin>date de début), il faut passer l'id de l'événement concerné dnas l'url
        </h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/event/{eventId}/suggestion/add</u></h5>
        <h6>Type de route : POST</h6>
        <h6>Exemple de corps de requête : <br>
            <code>
                { <br>
                "product":"coca cola"<br>
                } <br><br>
            </code>
            Cette requête permet à un organisateur d'ajouter une suggestion à un événement qui lui appartient et qui est privé <u>et</u> dans un lieu privé, il faut passer l'id de l'événement concerné dans l'url
        </h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/event/{eventId}/suggestions</u></h5>
        <h6>Type de route : GET</h6>
        <h6>Cette requête retourne une liste des suggestions d'un événement privé dans un lieu privé dont l'utilisateur en cours est un participant, il faut indiquer l'id de l'événement dans l'url</h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/event/{eventId}/contribution/add</u></h5>
        <h6>Type de route : POST</h6>
        <h6>Exemple de corps de requête : <br>
            <code>
                { <br>
                "product":"coca cola"<br>
                } <br><br>
            </code>
            Cette requête permet à un participant d'ajouter une contribution seule à un événement privé et dans un lieu privé. La contribution sera liée à cet utlisateur et il faut passer l'id de l'événement concerné dans l'url
        </h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/event/{eventId}/suggestion/{suggestionId}/addtocontribution</u></h5>
        <h6>Type de route : POST</h6>
        <h6>Cette requête permet à un participant d'ajouter une contribution en choisissant une suugestion de l'organisateur à un événement privé et dans un lieu privé. La contribution sera liée à cet utlisateur et à la suggestion dont elle provient et il faut passer l'id de l'événement concerné et l'id de la suggestion dans l'url</h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/event/{eventId}/contributions</u></h5>
        <h6>Type de route : GET</h6>
        <h6>Cette requête retourne une liste des contributions d'un événement privé dans un lieu privé dont l'utilisateur en cours est un participant, il faut indiquer l'id de l'événement dans l'url</h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/event/{eventId}/contribution/{contributionId}/edit</u></h5>
        <h6>Type de route : PUT</h6>
        <h6>Exemple de corps de requête : <br>
            <code>
                { <br>
                "product":"poulet"<br>
                } <br><br>
            </code>
            Cette requête permet à un participant de modifier une contribution seule à un événement privé et dans un lieu privé, il faut passer l'id de l'événement concerné et l'id de la contribution dans l'url
        </h6>
    </div>
        <div class="border border-dark p-3 m-3">
        <h5><u>https://partiel.jeremyduc.com/api/event/{eventId}/contribution/{contributionId}/remove</u></h5>
        <h6>Type de route : DELETE</h6>
        <h6>Cette requête permet à un participant de supprimer une contribution liée à une suggestion (donc mettre à jour la suggestion) à un événement privé et dans un lieu privé et à un organisateur de supprimer une contribution seule d'un participant, il faut passer l'id de l'événement concerné et l'id de la contribution dans l'url</h6>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>
