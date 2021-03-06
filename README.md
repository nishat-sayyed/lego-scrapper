<br />
<p align="center">
  <h3 align="center">Lego Scrapper</h3>

  <p align="center">
    Scrapper to crawl <a href="https://www.lego.com/en-gb/categories/retiring-soon">UK</a> and <a href="https://www.lego.com/en-us/categories/retiring-soon">US</a> Lego Retiring Soon pages
  </p>
</p>

<!-- TABLE OF CONTENTS -->
<details open="open">
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>

<!-- ABOUT THE PROJECT -->

## About The Project

[![Lego scrapper screenshot][product-screenshot]](https://github.com/nishat-sayyed/lego-scrapper/blob/main/screenshot.png)

LEGO is a very popular toy company. They have a huge number of products and occasionally decide to stop producing some of these products. The LEGO website has a list of products that are no longer in production and will soon be unavailable for purchase. They describe these products as ‘retiring soon’.

We’d like to build a tool to monitor when products are added to the list of products soon to be retired. Our idea is that as soon as a product is added to the list, we will purchase a number of the units available, and then sell them in the future for a higher price when the product becomes harder for customers to purchase.

This is a simple tool that will monitor the LEGO website and record when new products are added to the "Retiring Soon" page.

This will involve scraping specific URLs on a regular basis, reporting the data with a simple UI, and sending email alerts.

It collects the following attributes

-   Marketplace (UK or US)
-   Product name
-   Product Price
-   Sale Price
-   Discount amount
-   Discount percentage
-   Product details page URL
-   Stock status (Available/Out of stock)

After performing an update, the tool can send an email alert (to the email stored in MAIL_RECIPEINT environment variable) with a summary of any new products discovered, removal or updation of existing products.

### Built With

This section should list any major frameworks that you built your project using. Leave any add-ons/plugins for the acknowledgements section. Here are a few examples.

-   [Laravel](https://laravel.com)
-   [Goutte](https://github.com/FriendsOfPHP/Goutte)
-   [Tailwind](https://tailwindcss.com/)
-   [VueJs](https://vuejs.org/)
-   [MySQL](https://vuejs.org/)

<!-- GETTING STARTED -->

## Getting Started

To get a local copy up and running follow these simple example steps.

### Prerequisites

This is an example of how to list things you need to use the software and how to install them.

-   npm
    ```sh
    npm install npm@latest -g
    ```
-   Install [composer](https://getcomposer.org/)

### Installation

1. Clone the repo
    ```sh
    git clone https://github.com/your_username_/Project-Name.git
    ```
2. Install NPM packages
    ```sh
    npm install
    ```
3. Install composer packages
    ```sh
    composer install
    ```
4. Run migrations
    ```sh
    php artisan migrate
    ```

<!-- USAGE EXAMPLES -->

## Usage

1. Boot the local server
    ```sh
    php artisan serve
    ```
2. Serve static assets locally using webpack (different terminal)
    ```sh
    npm run watch
    ```

To run the scraper manually, you can use the following command

```sh
php artisan scrape:lego
```

You can specify the market using the <code>market</code> option. (Available options: uk, us)

```sh
php artisan scrape:lego --market=us
```

If you want to scrape website without receiving email updates use the <code>silent</code> option

```sh
php artisan scrape:lego --market=us --silent
```

<!-- LICENSE -->

## License

Distributed under the MIT License. See `LICENSE` for more information.

<!-- CONTACT -->

## Contact

Nishat Sayyed - [LinkedIn](https://www.linkedin.com/in/nishat-sayyed/)

Email - nishatsayyed26@gmail.com

Project Link: [https://github.com/your_username/repo_name](https://github.com/your_username/repo_name)

<!-- MARKDOWN LINKS & IMAGES -->

[linkedin-url]: https://www.linkedin.com/in/nishat-sayyed/
[product-screenshot]: screenshot.png
