<script setup>
import MobileNavbar from "@/Components/MobileNavbar.vue";
import NavbarWeb from "@/Components/NavbarWeb.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import Footer from "@/Components/Footer.vue";
import { Icon } from "@iconify/vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    bookings: {
        type: Object,
        default: () => ({}),
    },
    auth: {
        type: Object,
    },
});

const download = (id) => {
    router.get(`/invoice/${id}`);
};

const handlePay = (id) => {
    router.post(`/history`, {
        id: id,
        blacklist_id: props.auth.user.blacklist_id,
    });
};
</script>

<template>
    <Head title="Mount Agung | History" />
    <div>
        <div class="bg-gray-50 relative">
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
                        <button>
                            <Link :href="route('booking.index')">
                                <Icon
                                    class="ml-6 font-medium text-4xl hover:bg-black hover:text-white rounded-2xl"
                                    icon="solar:round-arrow-left-outline"
                                />
                            </Link>
                        </button>
                        <table
                            class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                        >
                            <thead
                                class="text-xs text-gray-700 uppercase dark:bg-gray-700 dark:text-gray-400"
                            >
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-center"
                                    >
                                        Actions
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Order Date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Full Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Check In Date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Check Out Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="booking in bookings.data"
                                    :key="booking.id"
                                    class="bg-white border-b"
                                >
                                    <td class="px-6 py-4 flex justify-center">
                                        <button
                                            v-if="booking.status == 'Paid'"
                                            class="text-white bg-[#DF6951] font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                            @click.prevent="
                                                download(`${booking.id}`)
                                            "
                                        >
                                            E-Ticket
                                        </button>
                                        <button
                                            v-else
                                            class="text-white bg-[#DF6951] font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                            @click.prevent="
                                                handlePay(`${booking.id}`)
                                            "
                                        >
                                            Pay Now
                                        </button>
                                    </td>
                                    <th
                                        scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                    >
                                        {{ booking.status }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ booking.order_date }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ booking.full_name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ booking.check_in }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ booking.check_out }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <Pagination :links="bookings.links" />
                    </div>
                </div>
            </div>

            <!-- <Footer class="bottom-0 absolute w-full" /> -->
        </div>
    </div>
</template>
