"use client";
import { OrderItems } from "@/shared/cart/order-items";
import { OrderSummary } from "@/shared/cart/order-summary";
import { PaymentOptions } from "@/shared/cart/payment-options";
import { ShippingForm } from "@/shared/cart/shipping-form";
import { PageHeader } from "@/shared/components";
import React, { useState } from "react";

export default function CartPage() {
  const [cartItems, setCartItems] = useState([
    {
      id: 1,
      name: "THE NAPKING",
      description: "Set 4 tovaglioli - Summerfruit",
      price: 85.0,
      quantity: 1,
      color: "Fantasia",
      image:
        "https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=150&h=150&fit=crop",
      category: "Stile selezione",
    },
    {
      id: 2,
      name: "INFERMENTUM",
      description: "Biscotti arachidi",
      price: 9.9,
      quantity: 2,
      image:
        "https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=150&h=150&fit=crop",
      category: "Alimentari",
    },
    {
      id: 3,
      name: "PIP STUDIO",
      description: "Copripiumino viva la vida blu 255x200",
      price: 250.0,
      quantity: 1,
      color: "Blu",
      image:
        "https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=150&h=150&fit=crop",
      category: "Camera",
    },
  ]);

  const [shippingDetails, setShippingDetails] = useState({
    firstName: "",
    lastName: "",
    birthDate: "",
    phone: "",
    email: "",
    address: "",
    city: "",
    cap: "",
    notes: "",
  });

  const [billingType, setBillingType] = useState("privato");
  const [paymentMethod, setPaymentMethod] = useState("");
  const [promoCode, setPromoCode] = useState("");
  const [wantInsurance, setWantInsurance] = useState(false);

  const updateQuantity = (id, newQuantity) => {
    if (newQuantity < 1) return;
    setCartItems((items) =>
      items.map((item) =>
        item.id === id ? { ...item, quantity: newQuantity } : item,
      ),
    );
  };

  const removeItem = (id) => {
    setCartItems((items) => items.filter((item) => item.id !== id));
  };

  const handleInputChange = (field, value) => {
    setShippingDetails((prev) => ({ ...prev, [field]: value }));
  };

  // Calculations
  const subtotal = cartItems.reduce(
    (sum, item) => sum + item.price * item.quantity,
    0,
  );
  const iva = subtotal * 0.22;
  const shipping = subtotal > 120 ? 0 : 5.8;
  const insurance = wantInsurance ? 2.0 : 0;
  const total = subtotal + iva + shipping + insurance;

  return (
    <div className="min-h-screen py-8">
      <div className="max-w-7xl mx-auto px-4">
        <PageHeader title="Carrello" />

        <div className="grid lg:grid-cols-2 gap-6">
          {/* Left Column - Order Items */}
          <div className="lg:col-span-1">
            <OrderItems
              items={cartItems}
              onUpdateQuantity={updateQuantity}
              onRemove={removeItem}
            />
          </div>

          {/* Right Column - Forms and Summary */}
          <div className="space-y-6">
            <ShippingForm
              shippingDetails={shippingDetails}
              onInputChange={handleInputChange}
            />

            <OrderSummary
              subtotal={subtotal}
              iva={iva}
              shipping={shipping}
              insurance={insurance}
              total={total}
            />

            <PaymentOptions
              subtotal={subtotal}
              wantInsurance={wantInsurance}
              onInsuranceToggle={setWantInsurance}
              promoCode={promoCode}
              onPromoCodeChange={setPromoCode}
              billingType={billingType}
              onBillingTypeChange={setBillingType}
              paymentMethod={paymentMethod}
              onPaymentMethodChange={setPaymentMethod}
            />
          </div>
        </div>
      </div>
    </div>
  );
}
