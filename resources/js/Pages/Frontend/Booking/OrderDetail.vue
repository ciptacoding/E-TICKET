<script setup>
import NavbarWeb from "@/Components/NavbarWeb.vue";
import MobileNavbar from "@/Components/MobileNavbar.vue";
import { Head, router } from "@inertiajs/vue3";
import Footer from "@/Components/Footer.vue";
import { onMounted } from "vue";
import { Notyf } from "notyf";
import "notyf/notyf.min.css";

const notyf = new Notyf({
    duration: 3000,
    position: {
        x: "center",
        y: "top",
    },
    types: [
        {
            type: "warning",
            background: "orange",
            icon: {
                className: "material-icons",
                tagName: "i",
                text: "warning",
            },
        },
        {
            type: "error",
            background: "indianred",
            duration: 3000,
            dismissible: true,
        },
    ],
});

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    auth: {
        type: Object,
    },
    booking: {
        type: Object,
    },
    snapToken: {
        type: String,
    },
    clientKey: {
        type: String,
    },
});

onMounted(() => {
    const midtransClientKey = props.clientKey;
    const script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "https://app.sandbox.midtrans.com/snap/snap.js";
    script.setAttribute("data-client-key", midtransClientKey);

    script.onload = () => {
        console.log("Midtrans Snap script loaded successfully!");
    };

    script.onerror = (error) => {
        console.error("Error loading Midtrans Snap script:", error);
    };

    document.head.appendChild(script);
});

const handlePayment = () => {
    const transactionToken = props.snapToken;

    window.snap.pay(transactionToken, {
        onSuccess: (result) => {
            // alert("Payment success!");
            // console.log(result);
            window.location.href = `/invoice/${props.booking.id}`;
        },
        onPending: (result) => {
            // console.log(result);
            notyf.open({
                type: "warning",
                message: "waiting for your payment",
            });
        },
        onError: (result) => {
            // console.log(result);
            notyf.error("Payment failed!");
        },
        onClose: () => {
            notyf.open({
                type: "warning",
                message: "You closed the popup without finishing the payment",
            });
        },
    });
};
</script>

<template>
    <Head title="Mount Agung | Booking" />
    <div>
        <div class="bg-gray-50 min-h-screen relative">
            <!-- navbar mobile view -->
            <MobileNavbar />
            <!-- navbar mobile view -->
            <NavbarWeb :can-login="canLogin" :can-register="canRegister" />

            <header
                class="bg-[url('/img/bg-gunung.png')] bg-center bg-cover h-[300px]"
            ></header>
            <div class="mt-[-5rem] mb-20">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div
                        class="relative overflow-x-auto rounded-md py-6 bg-white bg-clip-padding backdrop-filter backdrop-blur-md bg-opacity-50 shadow-md"
                    >
                        <h1
                            class="text-center font-semibold text-lg lg:text-xl text-[#DF6951] mb-2"
                        >
                            PAYMENT DETAIL
                        </h1>
                        <hr />
                        <div
                            class="grid grid-cols-1 lg:grid-cols-2 p-2 sm:pt-6 sm:px-12"
                        >
                            <div>
                                <img
                                    src="/img/detail-order.png"
                                    alt="traveller"
                                    class="h-[14rem]"
                                />
                            </div>
                            <div>
                                <div class="flex justify-between">
                                    <p class="font-bold">Full Name</p>
                                    <p>{{ booking.full_name }}</p>
                                </div>
                                <hr />
                                <div class="flex justify-between mt-2">
                                    <p class="font-bold">Address</p>
                                    <p>{{ booking.address }}</p>
                                </div>
                                <hr />
                                <div class="flex justify-between mt-2">
                                    <p class="font-bold">Phone</p>
                                    <p>{{ booking.phone }}</p>
                                </div>
                                <hr />
                                <div class="flex justify-between mt-2">
                                    <p class="font-bold">Gender</p>
                                    <p>{{ booking.gender }}</p>
                                </div>
                                <hr />
                                <div class="flex justify-between mt-2">
                                    <p class="font-bold">Check In</p>
                                    <p>{{ booking.check_in }}</p>
                                </div>
                                <hr />
                                <div class="flex justify-between mt-2">
                                    <p class="font-bold">Check Out</p>
                                    <p>{{ booking.check_out }}</p>
                                </div>
                                <hr />
                                <div class="flex justify-between mt-2">
                                    <p class="font-bold">Total Price</p>
                                    <p>Rp. {{ booking.total_price }}</p>
                                </div>
                                <hr />
                                <div class="flex justify-end">
                                    <button
                                        type="submit"
                                        @click="handlePayment"
                                        class="flex items-center text-white bg-[#DF6951] font-medium rounded-lg text-sm px-5 py-2.5 text-center mt-4"
                                    >
                                        Pay Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <Footer class="bottom-0 md:absolute sm:block w-full" />
        </div>
    </div>
</template>
