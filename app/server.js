//Load express module with `require` directive
var express = require('express')
var app = express()

//Define request response in root URL (/)
app.get('/', function (req, res) {
  // res.send('Hello World test 5!')
  res.sendFile(__dirname + '/html/index.html')
})

//Launch listening server on port 8081
app.listen(3000, function () {
  console.log('app listening on port 3000! with nodemon')
  console.log(process.env)
})