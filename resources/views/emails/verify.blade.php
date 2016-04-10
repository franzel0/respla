<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Bitte bestätigen Sie Ihre E-Mail</h2>

        <div>
            Vielen Dank für das Erstellen einer Einrichtung. Bitte klicken Sie den folgenden link an, um Ihre Anmeldung abzuschliessen.
            {{ URL::to('register/verify/' . $confirmation_code) }}.<br/>

        </div>

    </body>
</html>