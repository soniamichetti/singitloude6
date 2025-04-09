git clone https://github.com/soniamichetti/film_rating.git
cd film_rating/frontend
npm install
npm run serve
cd ../backend
npm install
npm run serve
cd ../../
mysql -u root
CREATE DATABASE film_rating_db;
use film_rating_db
exit
mysql -u root -p film_rating_db < film_rating_db.sql
http://localhost:8080
