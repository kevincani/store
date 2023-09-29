<x-admin-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                text-align: center;
                margin: 0;
                padding: 20px;
            }

            .container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #fff;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }

            h1 {
                color: #333;
            }

            p {
                color: #555;
                font-size: 18px;
                margin-bottom: 20px;
            }

            .btn {
                display: inline-block;
                padding: 10px 20px;
                background-color: #007bff;
                color: #fff;
                text-decoration: none;
                border-radius: 5px;
                font-size: 16px;
                margin-top: 20px;
                transition: background-color 0.3s;
            }

            .btn:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <h1>Order Confirmation</h1>
        <p>Thanks for your order! You have just completed your payment. The seller will reach out to you as soon as possible.</p>
        <a href="/" class="btn">Continue Shopping</a>
    </div>
    </body>
    </html>
</x-admin-layout>
