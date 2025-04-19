// "use client";
// import { PayPalScriptProvider, PayPalButtons } from "@paypal/react-paypal-js";

// function HomePage() {
//   return (
//     <div className="h-screen bg-slate-950 flex justify-center items-center">
//       <PayPalScriptProvider options={{
//         clientId:"AYhM7Gub_jwpY34KhU90XB7F6AlZOCZmitUmdZ4qjZTzkXFF6L-KXjBpJLXeA8gVDs0zYvjaiBADujQQ"
//       }}>
//         <PayPalButtons 
//         style={{
//           color: "blue",
//           layout: "horizontal",
      
//         }}
//         createOrder={async () => {
//           const res = await fetch('/api/checkout',{
//             method: "POST"
//           } )
//           const order = await res.json()
//           console.log(order)
//           return order.id
//         }}onApprove={(data, actions) => {
//           console.log(data)
//           actions.order.capture()
//         }}
//         onCancel={(data) => {
//           console.log("Cancelado:", data)
//         }}
//         />
//       </PayPalScriptProvider>
//     </div>
//   );
// }
// export default HomePage;


// // createOrder={() => {}}
// //         onCancel={()=> {}}
// //         onApprove={()=> {}}

  import paypal from '@paypal/checkout-server-sdk';

const clientId = process.env.PAYPAL_CLIENT_ID;
const clientSecret = process.env.PAYPAL_CLIENT_SECRET;

const environment = new paypal.core.SandboxEnvironment(clientId, clientSecret);
const client = new paypal.core.PayPalHttpClient(environment);

export default async function handler(req, res) {
    if (req.method === 'POST') {
        const request = new paypal.orders.OrdersCreateRequest();
        request.prefer("return=representation");
        request.requestBody({
            intent: 'CAPTURE',
            purchase_units: [{
                amount: {
                    currency_code: 'PEN',
                    value: req.body.total
                }
            }]
        });

        try {
            const order = await client.execute(request);
            res.status(200).json({ id: order.result.id });
        } catch (error) {
            console.error(error);
            res.status(500).json({ error: error.message });
        }
    } else {
        res.setHeader('Allow', 'POST');
        res.status(405).end('Method Not Allowed');
    }
}

import React from 'react';

const HomePage = () => {
    return (
        <div>
            <h1>Página Principal</h1>
        </div>
    );
};


