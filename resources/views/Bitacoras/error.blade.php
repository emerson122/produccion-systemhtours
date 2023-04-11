<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB"
    crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
  <title>400 | Page not found</title>
</head>
<body>
  <header>
    <nav class="bg-info pt-3 pb-3">
      <div class="container">
        <h3 class="text-white">Error</h3>
      </div>
    </nav>
    <div class="bg-light text-center pt-5" style="height: 100vh;">
      <div class="container">
        <h1 class="display-1 pt-5 font-weight-bold">Error</h1>
        <h1 class="display-4 pt-1 pb-3">{{ Session::get('error') }}</h1>
        <h3 class="font-weight-light text-secondary">Favor consultar a un administrador </h3>
        <a href="{{ route('Auth.login') }}" class="btn btn-info mt-3 pt-3 pb-3 pr-4 pl-4">Volver a inicio</a>
      </div>
    </div>
  </header>

</body>
</html>