# API Reference

## Concerts

- `GET /API/concerts/read.php` – List all concerts
- `GET /API/concerts/read_single.php?id=1` – Get single concert
- `POST /API/concerts/create.php` – Create concert
- `PUT /API/concerts/update.php` – Update concert
- `DELETE /API/concerts/delete.php` – Delete concert
- `GET /API/concerts/search.php?term=rock` – Search concerts
- `GET /API/concerts/count.php` – Count concerts

## Bookings

- `POST /API/bookings/book_ticket.php`
- `GET /API/bookings/get_user_bookings.php?user_id=1`
- `GET /API/bookings/get_all_bookings.php`
- `POST /API/bookings/cancel_booking.php`

## Favorites

- `POST /API/favourites/add.php`
- `GET /API/favourites/get.php?user_id=1`
- `POST /API/favourites/remove.php`

## Spotify

- `POST /API/Spotify/auth.php`
- `GET /API/Spotify/search_artist.php?artist=Eminem`

## Users

- `POST /API/users/update.php`
