<?php
require_once 'config/database.php';

// Initialize cars array
$cars = [];

// Get car data from database
try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE status = 'available' ORDER BY brand, name");
    $stmt->execute();
    $cars = $stmt->fetchAll();
} catch(PDOException $e) {
    $cars = [];
}

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
    $search_lower = mb_strtolower($search);
    $filtered_cars = [];
    foreach ($cars as $car) {
        if (mb_stripos($car['name'], $search) !== false || mb_stripos($car['brand'], $search) !== false) {
            $filtered_cars[] = $car;
        }
    }
    $cars = $filtered_cars;
}

// Group cars by brand
$cars_by_brand = [];
if (is_array($cars) && !empty($cars)) {
    foreach ($cars as $car) {
        $brand = $car['brand'];
        if (!isset($cars_by_brand[$brand])) {
            $cars_by_brand[$brand] = [];
        }
        $cars_by_brand[$brand][] = $car;
    }
}

// If no cars from database, use static data
if (empty($cars_by_brand)) {
    $static_cars_by_brand = [
        'Tesla' => [
            ['id' => 1, 'name' => 'Tesla Model S', 'price' => 1830000, 'image' => 'assets/deals-1.png', 'rating' => 4.5, 'reviews' => 550, 'features' => ['seats' => 4, 'transmission' => 'Autopilot', 'range' => '400km', 'fuel_type' => 'Electric']],
            ['id' => 2, 'name' => 'Tesla Model E', 'price' => 2530000, 'image' => 'assets/deals-2.png', 'rating' => 4.4, 'reviews' => 450, 'features' => ['seats' => 4, 'transmission' => 'Autopilot', 'range' => '400km', 'fuel_type' => 'Electric']],
            ['id' => 3, 'name' => 'Tesla Model Y', 'price' => 1530000, 'image' => 'assets/deals-3.png', 'rating' => 4.5, 'reviews' => 550, 'features' => ['seats' => 4, 'transmission' => 'Autopilot', 'range' => '400km', 'fuel_type' => 'Electric']]
        ],
        'Mitsubishi' => [
            ['id' => 4, 'name' => 'Mirage', 'price' => 530000, 'image' => 'assets/deals-4.png', 'rating' => 4.3, 'reviews' => 350, 'features' => ['seats' => 4, 'transmission' => 'Manual', 'mileage' => '18km/l', 'fuel_type' => 'Diesel']],
            ['id' => 5, 'name' => 'Xpander', 'price' => 1000000, 'image' => 'assets/deals-5.png', 'rating' => 4.2, 'reviews' => 250, 'features' => ['seats' => 4, 'transmission' => 'Manual', 'mileage' => '18km/l', 'fuel_type' => 'Diesel']],
            ['id' => 6, 'name' => 'Pajero Sports', 'price' => 1500000, 'image' => 'assets/deals-6.png', 'rating' => 4.1, 'reviews' => 150, 'features' => ['seats' => 4, 'transmission' => 'Manual', 'mileage' => '18km/l', 'fuel_type' => 'Diesel']]
        ],
        'Mazda' => [
            ['id' => 7, 'name' => 'Mazda CX5', 'price' => 800000, 'image' => 'assets/deals-7.png', 'rating' => 4.0, 'reviews' => 200, 'features' => ['seats' => 4, 'transmission' => 'Manual', 'mileage' => '18km/l', 'fuel_type' => 'Diesel']],
            ['id' => 8, 'name' => 'Mazda CX-30', 'price' => 1230000, 'image' => 'assets/deals-8.png', 'rating' => 4.0, 'reviews' => 100, 'features' => ['seats' => 4, 'transmission' => 'Manual', 'mileage' => '18km/l', 'fuel_type' => 'Diesel']],
            ['id' => 9, 'name' => 'Mazda CX-9', 'price' => 1510000, 'image' => 'assets/deals-9.png', 'rating' => 4.1, 'reviews' => 180, 'features' => ['seats' => 4, 'transmission' => 'Manual', 'mileage' => '18km/l', 'fuel_type' => 'Diesel']]
        ],
        'Toyota' => [
            ['id' => 10, 'name' => 'Corolla', 'price' => 1530000, 'image' => 'assets/deals-10.png', 'rating' => 4.2, 'reviews' => 250, 'features' => ['seats' => 4, 'transmission' => 'Manual', 'mileage' => '18km/l', 'fuel_type' => 'Diesel']],
            ['id' => 11, 'name' => 'Innova', 'price' => 2050000, 'image' => 'assets/deals-11.png', 'rating' => 4.5, 'reviews' => 550, 'features' => ['seats' => 4, 'transmission' => 'Manual', 'mileage' => '18km/l', 'fuel_type' => 'Diesel']],
            ['id' => 12, 'name' => 'Fortuner', 'price' => 2530000, 'image' => 'assets/deals-12.png', 'rating' => 4.1, 'reviews' => 180, 'features' => ['seats' => 4, 'transmission' => 'Manual', 'mileage' => '18km/l', 'fuel_type' => 'Diesel']]
        ],
        'Honda' => [
            ['id' => 13, 'name' => 'Amaze', 'price' => 860000, 'image' => 'assets/deals-13.png', 'rating' => 4.0, 'reviews' => 200, 'features' => ['seats' => 4, 'transmission' => 'Manual', 'mileage' => '18km/l', 'fuel_type' => 'Diesel']],
            ['id' => 14, 'name' => 'Elevate', 'price' => 900000, 'image' => 'assets/deals-14.png', 'rating' => 4.3, 'reviews' => 350, 'features' => ['seats' => 4, 'transmission' => 'Manual', 'mileage' => '18km/l', 'fuel_type' => 'Diesel']],
            ['id' => 15, 'name' => 'City', 'price' => 1000000, 'image' => 'assets/deals-15.png', 'rating' => 4.3, 'reviews' => 300, 'features' => ['seats' => 4, 'transmission' => 'Manual', 'mileage' => '18km/l', 'fuel_type' => 'Diesel']]
        ]
    ];
    if ($search !== '') {
        $cars_by_brand = [];
        foreach ($static_cars_by_brand as $brand => $brand_cars) {
            foreach ($brand_cars as $car) {
                if (mb_stripos($car['name'], $search) !== false || mb_stripos($brand, $search) !== false) {
                    $cars_by_brand[$brand][] = $car;
                }
            }
        }
        // Remove empty brands
        foreach ($cars_by_brand as $brand => $brand_cars) {
            if (empty($brand_cars)) unset($cars_by_brand[$brand]);
        }
    } else {
        $cars_by_brand = $static_cars_by_brand;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
    />
    <link rel="stylesheet" href="styles.css" />
    <title>Cars24 | Car Buying</title>
  </head>
  <body>
    <header>
      <nav>
        <div class="nav__header">
          <div class="nav__logo">
            <a href="#" class="logo">
              <img src="assets/logo-white.png" alt="logo" class="logo-white" />
              <img src="assets/logo-dark.png" alt="logo" class="logo-dark" />
              <span>Cars24</span>
            </a>
          </div>
          <div class="nav__menu__btn" id="menu-btn">
            <i class="ri-menu-line"></i>
          </div>
        </div>
        <ul class="nav__links" id="nav-links">
          <li><a href="#home">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#deals">Buying Deals</a></li>
          <li><a href="#choose">Why Choose Us</a></li>
        </ul>
        <form class="nav__search" action="index.php" method="get" style="display: flex; align-items: center; gap: 0.5rem; margin-left: 1rem;">
          <input type="text" name="search" placeholder="Search cars..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="padding: 0.4rem 0.8rem; border-radius: 4px; border: 1px solid #ccc;" />
          <button type="submit" class="btn" style="padding: 0.4rem 1rem;">Search</button>
        </form>
        <div class="nav__btns">
          <?php if (isLoggedIn()): ?>
            <span style="color: var(--white); margin-right: 1rem;">Welcome, <?php echo htmlspecialchars(getCurrentUser()['full_name']); ?></span>
            <a href="logout.php"><button class="btn">Logout</button></a>
          <?php else: ?>
            <a href="login.php"><button class="btn">Login</button></a>
          <?php endif; ?>
        </div>        
      </nav>
      <div class="header__container" id="home">
        <div class="header__image">
          <img src="assets/header.png" alt="header" />
        </div>
        <div class="header__content">
          <h2>👍 100% Trusted car buying platform in India</h2>
          <h1>FAST AND EASY WAY TO BUY A CAR</h1>
          <p class="section__description">
            Discover a seamless car Buying experience with us. Choose from a
            range of premium vehicles to suit your style and needs, and hit the
            road with confidence. Quick, easy, and reliable - Buy your ride
            today!
          </p>
        </div>
      </div>
    </header>

    <section class="section__container about__container" id="about">
      <h2 class="section__header">How it works</h2>
      <p class="section__description">
        Buying a car with us is simple! Browse our selection, choose your perfect vehicle,
        and complete your purchase. We'll handle the rest, ensuring a smooth
        car buying experience.
      </p>
      <div class="about__grid">
        <div class="about__card">
          <span><i class="ri-search-line"></i></span>
          <h4>Browse & Select</h4>
          <p>
            Explore our wide range of quality cars from trusted brands. Find the perfect
            vehicle that matches your style, budget, and requirements.
          </p>
        </div>
        <div class="about__card">
          <span><i class="ri-shopping-cart-fill"></i></span>
          <h4>Make Purchase</h4>
          <p>
            Choose your preferred payment method and complete the purchase securely.
            Our streamlined process makes buying a car quick and hassle-free.
          </p>
        </div>
        <div class="about__card">
          <span><i class="ri-truck-fill"></i></span>
          <h4>Get Delivery</h4>
          <p>
            We'll deliver your car to your doorstep on your preferred date.
            Enjoy your new vehicle with our comprehensive support and warranty.
          </p>
        </div>
      </div>
    </section>

    <section class="deals" id="deals">
      <div class="section__container deals__container">
        <h2 class="section__header">Most popular car buying deals</h2>
        <p class="section__description">
          Explore our top car Buying deals, handpicked to give you the best
          value and experience. Book now and drive your favorite ride at an
          incredible rate!
        </p>
        <div class="deals__tabs">
          <?php 
          $first_brand = true;
          if (is_array($cars_by_brand)) {
            foreach (array_keys($cars_by_brand) as $brand): 
          ?>
            <button class="btn <?php echo $first_brand ? 'active' : ''; ?>" data-id="<?php echo $brand; ?>"><?php echo $brand; ?></button>
          <?php 
            $first_brand = false;
            endforeach; 
          }
          ?>
        </div>
        
        <?php 
        $first_brand = true;
        if (is_array($cars_by_brand)) {
          foreach ($cars_by_brand as $brand => $brand_cars): 
        ?>
        <div id="<?php echo $brand; ?>" class="tab__content <?php echo $first_brand ? 'active' : ''; ?>">
          <?php if (is_array($brand_cars)): ?>
            <?php foreach ($brand_cars as $car): ?>
            <div class="deals__card">
              <img src="<?php echo htmlspecialchars($car['image']); ?>" alt="deals" />
              <div class="deals__rating">
                <?php 
                $rating = isset($car['rating']) ? $car['rating'] : 4.0;
                $reviews = isset($car['reviews']) ? $car['reviews'] : 100;
                for ($i = 1; $i <= 5; $i++): 
                ?>
                  <span><i class="ri-star-<?php echo $i <= $rating ? 'fill' : 'line'; ?>"></i></span>
                <?php endfor; ?>
                <span>(<?php echo $reviews; ?>)</span>
              </div>
              <h4><?php echo htmlspecialchars($car['name']); ?></h4>
              <div class="deals__card__grid">
                <?php 
                $features = isset($car['features']) ? $car['features'] : ['seats' => 4, 'transmission' => 'Manual', 'mileage' => '18km/l', 'fuel_type' => 'Diesel'];
                $feature_icons = ['seats' => 'ri-group-line', 'transmission' => 'ri-steering-2-line', 'mileage' => 'ri-speed-up-line', 'range' => 'ri-speed-up-line', 'fuel_type' => 'ri-car-line'];
                $feature_labels = ['seats' => 'People', 'transmission' => 'Transmission', 'mileage' => 'Mileage', 'range' => 'Range', 'fuel_type' => 'Fuel Type'];
                if (is_array($features)) {
                  foreach ($features as $key => $value): 
                ?>
                <div>
                  <span><i class="<?php echo $feature_icons[$key] ?? 'ri-car-line'; ?>"></i></span> 
                  <?php echo $value . ' ' . ($feature_labels[$key] ?? ucfirst($key)); ?>
                </div>
                <?php 
                  endforeach; 
                }
                ?>
              </div>
              <hr />
              <div class="deals__card__footer">
                <h3>₹<?php echo number_format($car['price']); ?><span></span></h3>
                <?php if (isLoggedIn()): ?>
                  <a href="purchase.php?car_id=<?php echo $car['id']; ?>">
                    Buy Now
                    <span><i class="ri-arrow-right-line"></i></span>
                  </a>
                <?php else: ?>
                  <a href="login.php">
                    Login to Buy
                    <span><i class="ri-arrow-right-line"></i></span>
                  </a>
                <?php endif; ?>
              </div>
            </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <?php 
          $first_brand = false;
          endforeach; 
        }
        ?>
      </div>
    </section>

    <section class="choose__container" id="choose">
      <div class="choose__image">
        <img src="assets/choose.png" alt="choose" />
      </div>
      <div class="choose__content">
        <h2 class="section__header">Why choose us</h2>
        <p class="section__description">
          Discover the difference with our car buying service. We offer reliable
          vehicles, exceptional customer service, and competitive pricing to
          ensure a seamless rental experience.
        </p>
        <div class="choose__grid">
          <div class="choose__card">
            <span><i class="ri-customer-service-line"></i></span>
            <div>
              <h4>Customer Support</h4>
              <p>Our dedicated support team is available to assist you 24/7.</p>
            </div>
          </div>
          <div class="choose__card">
            <span><i class="ri-map-pin-line"></i></span>
            <div>
              <h4>Many Locations</h4>
              <p>Convenient pick-up and drop-off locations to suit your travel needs.</p>
            </div>
          </div>
          <div class="choose__card">
            <span><i class="ri-wallet-line"></i></span>
            <div>
              <h4>Best Price</h4>
              <p>Enjoy competitive rates and great value for every buying.</p>
            </div>
          </div>
          <div class="choose__card">
            <span><i class="ri-verified-badge-line"></i></span>
            <div>
              <h4>Verified Brands</h4>
              <p>Choose from trusted and well-maintained car brands.</p>
            </div>
          </div>
          <div class="choose__card">
            <span><i class="ri-calendar-close-line"></i></span>
            <div>
              <h4>Free Cancellations</h4>
              <p>Flexible bookings with free cancellation options.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section__container client__container" id="client">
      <h2 class="section__header">What people say about us?</h2>
      <p class="section__description">
        Discover why our customers love buying with us! Read real reviews and
        testimonials to see how we deliver exceptional service.
      </p>
      <div class="swiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <div class="client__card">
              <div class="client__details">
                <img src="assets/client-1.jpg" alt="client" />
                <div>
                  <h4>Anvi Holani</h4>
                  <div class="client__rating">
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-line"></i></span>
                  </div>
                </div>
              </div>
              <p>I had an amazing experience buying a car from this service. The booking process was quick and easy, and the car was in perfect condition. Highly recommend!</p>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="client__card">
              <div class="client__details">
                <img src="assets/client-2.jpg" alt="client" />
                <div>
                  <h4>Utkarsh Sawant</h4>
                  <div class="client__rating">
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-line"></i></span>
                  </div>
                </div>
              </div>
              <p>Customer support was excellent! They helped me with all my questions, and I felt confident about my booking. I will definitely buy from them again.</p>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="client__card">
              <div class="client__details">
                <img src="assets/client-3.jpg" alt="client" />
                <div>
                  <h4>Palakh Bhaykar</h4>
                  <div class="client__rating">
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-line"></i></span>
                  </div>
                </div>
              </div>
              <p>Affordable prices and great selection of vehicles! I found exactly what I needed, and the pick-up and drop-off process was seamless. Very happy with my buying.</p>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="client__card">
              <div class="client__details">
                <img src="assets/client-4.jpg" alt="client" />
                <div>
                  <h4>Rohan Patil</h4>
                  <div class="client__rating">
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-line"></i></span>
                  </div>
                </div>
              </div>
              <p>The flexibility of free cancellations made my trip stress-free. I ended up changing my plans, and it was no hassle to adjust my booking. Great service overall!</p>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="client__card">
              <div class="client__details">
                <img src="assets/client-5.jpg" alt="client" />
                <div>
                  <h4>Udip Gutte</h4>
                  <div class="client__rating">
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-fill"></i></span>
                    <span><i class="ri-star-line"></i></span>
                  </div>
                </div>
              </div>
              <p>The car I buy was top-notch, and the driver was very experienced. It made my road trip so much more enjoyable. Will use them again next time!</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer class="footer">
      <div class="section__container footer__container">
        <div class="footer__col">
          <div class="footer__logo">
            <a href="#" class="logo">
              <img src="assets/logo-white.png" alt="logo" />
              <span>Cars24</span>
            </a>
          </div>
          <p>We're here to provide you with the best vehicles and a seamless Buying experience. Stay connected for updates, special offers, and more. Drive with confidence!</p>
        </div>
        <div class="footer__col">
          <h4>Our Services</h4>
          <ul class="footer__links">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#deals">Buying Deals</a></li>
            <li><a href="#choose">Why Choose Us</a></li>
            <li><a href="#client">Testimonials</a></li>
          </ul>
        </div>
        <div class="footer__col">
          <h4>Vehicle Model</h4>
          <ul class="footer__links">
            <li><a href="#">Toyota Corolla</a></li>
            <li><a href="#">Toyota Noah</a></li>
            <li><a href="#">Toyota Allion</a></li>
            <li><a href="#">Toyota Premio</a></li>
            <li><a href="#">Mistubishi Pajero</a></li>
          </ul>
        </div>
        <div class="footer__col">
          <h4>Contact</h4>
          <ul class="footer__links">
            <li><a href="#"><span><i class="ri-phone-fill"></i></span> +91 8484827954</a></li>
            <li><a href="#"><span><i class="ri-map-pin-fill"></i></span> Nanded , India</a></li>
            <li><a href="#"><span><i class="ri-mail-fill"></i></span> info@Cars24</a></li>
          </ul>
        </div>
      </div>
    </footer>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="main.js"></script>
  </body>
</html>