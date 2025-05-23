import Image from 'next/image';
import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { ProductCard } from '@/shared/snippets/product-card';

const products = [
	{
		id: 1,
		brand: 'APEIREM',
		name: 'Restore serum',
		price: '24,50€',
		image: '/images/placeholder.svg',
		category: 'Viso',
		tag: '',
	},
	{
		id: 2,
		brand: 'APEIREM',
		name: 'Replenish Cleansing balm',
		price: '19,50€',
		image: '/images/placeholder.svg',
		category: 'Viso',
		tag: '',
	},
	{
		id: 3,
		brand: 'APEIREM',
		name: 'Replenish eye cream',
		price: '19,90€',
		image: '/images/placeholder.svg',
		category: 'Viso',
		tag: 'IN SCONTO',
	},
	{
		id: 4,
		brand: 'APEIREM',
		name: 'Calm face moisturiser',
		price: '22,50€',
		image: '/images/placeholder.svg',
		category: 'Viso',
		tag: '',
	},
	{
		id: 5,
		brand: 'BJORK & BERRIES',
		name: 'Hydrating face oil',
		price: '35,00€',
		image: '/images/placeholder.svg',
		category: 'Viso',
		tag: 'NUOVO',
	},
	{
		id: 6,
		brand: 'BJORK & BERRIES',
		name: 'Gentle cleansing gel',
		price: '28,50€',
		image: '/images/placeholder.svg',
		category: 'Viso',
		tag: '',
	},
];

export default function Home() {
	return (
		<div className="flex min-h-screen flex-col">
			<header className="sticky top-0 z-50 w-full border-b bg-white">
				<div className="container flex h-16 items-center justify-between py-4">
					<div className="flex items-center gap-6">
						<Link
							href="/"
							className="font-serif text-2xl font-light">
							stile
						</Link>
					</div>
					<div className="relative w-full max-w-sm px-4">
						<div className="relative">
							<input
								type="search"
								placeholder="Search..."
								className="w-full rounded-md border border-gray-300 py-2 pl-3 pr-10 focus:outline-none focus:ring-1 focus:ring-gray-400"
							/>
							<div className="absolute inset-y-0 right-0 flex items-center pr-3">
								<svg
									xmlns="http://www.w3.org/2000/svg"
									className="h-4 w-4 text-gray-400"
									fill="none"
									viewBox="0 0 24 24"
									stroke="currentColor">
									<path
										strokeLinecap="round"
										strokeLinejoin="round"
										strokeWidth={2}
										d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
									/>
								</svg>
							</div>
						</div>
					</div>
					<div className="flex items-center gap-4">
						<Button
							variant="ghost"
							size="icon">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								className="h-5 w-5"
								fill="none"
								viewBox="0 0 24 24"
								stroke="currentColor">
								<path
									strokeLinecap="round"
									strokeLinejoin="round"
									strokeWidth={2}
									d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
								/>
							</svg>
							<span className="sr-only">Account</span>
						</Button>
						<Button
							variant="ghost"
							size="icon">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								className="h-5 w-5"
								fill="none"
								viewBox="0 0 24 24"
								stroke="currentColor">
								<path
									strokeLinecap="round"
									strokeLinejoin="round"
									strokeWidth={2}
									d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
								/>
							</svg>
							<span className="sr-only">Cart</span>
						</Button>
					</div>
				</div>
			</header>

			<main className="flex-1">
				<section className="relative h-[300px] w-full bg-neutral-900 text-white">
					<div className="absolute inset-0">
						<Image
							src="/images/placeholder.svg?height=300&width=1200"
							alt="Face care"
							fill
							className="object-cover opacity-50"
						/>
					</div>
					<div className="relative flex h-full items-center justify-center">
						<h1 className="text-5xl font-light uppercase tracking-widest">VISO</h1>
					</div>
				</section>

				<div className="container py-6">
					<div className="flex items-center justify-between">
						<nav
							className="flex"
							aria-label="Breadcrumb">
							<ol className="flex items-center space-x-1 text-sm">
								<li>
									<Link
										href="#"
										className="text-gray-500 hover:text-gray-700">
										Fragrance and Beauty
									</Link>
								</li>
								<li>
									<span className="text-gray-400">&gt;</span>
								</li>
								<li>
									<span className="font-medium text-gray-900">Viso</span>
								</li>
							</ol>
						</nav>
						<div className="flex items-center gap-2">
							<span className="text-sm text-gray-500">FILTER</span>
							<Button
								variant="ghost"
								size="icon"
								className="h-8 w-8">
								<svg
									xmlns="http://www.w3.org/2000/svg"
									className="h-4 w-4"
									fill="none"
									viewBox="0 0 24 24"
									stroke="currentColor">
									<path
										strokeLinecap="round"
										strokeLinejoin="round"
										strokeWidth={2}
										d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
									/>
								</svg>
							</Button>
						</div>
					</div>

					<div className="mt-4 flex flex-wrap gap-4 border-b pb-4">
						<Link
							href="#"
							className="text-sm font-medium hover:underline">
							CREME GIORNO VISO
						</Link>
						<Link
							href="#"
							className="text-sm font-medium hover:underline">
							CREME VISO
						</Link>
						<Link
							href="#"
							className="text-sm font-medium hover:underline">
							SIERI VISO
						</Link>
						<Link
							href="#"
							className="text-sm font-medium hover:underline">
							OLIO VISO
						</Link>
						<Link
							href="#"
							className="text-sm font-medium hover:underline">
							MASCHERE VISO
						</Link>
						<Link
							href="#"
							className="text-sm font-medium hover:underline">
							PELLI NORMALI
						</Link>
						<Link
							href="#"
							className="text-sm font-medium hover:underline">
							NATURALE
						</Link>
						<Link
							href="#"
							className="text-sm font-medium hover:underline">
							PELLI SECCHE
						</Link>
						<Link
							href="#"
							className="text-sm font-medium hover:underline">
							PELLI PROBLEMATICHE
						</Link>
						<Link
							href="#"
							className="text-sm font-medium hover:underline">
							PELLI SENSIBILI
						</Link>
					</div>

					<h2 className="my-8 text-center text-2xl font-light">I Nuovi arrivi</h2>

					<div className="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
						{products.map((product) => (
							<ProductCard data={product} />
						))}
					</div>
				</div>
			</main>
		</div>
	);
}
