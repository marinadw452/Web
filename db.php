$url = getenv("DATABASE_URL");
$parts = parse_url($url);
$host = $parts['host'];
$port = $parts['port'];
$user = $parts['user'];
$pass = $parts['pass'];
$dbname = ltrim($parts['path'], '/');

$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$pass");
