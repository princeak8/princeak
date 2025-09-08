<template>
    <svg viewBox="0 0 400 120" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <!-- Gradient for the main text -->
            <linearGradient id="blueGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#4169E1;stop-opacity:1" />
            <stop offset="50%" style="stop-color:#1E90FF;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#0066CC;stop-opacity:1" />
            </linearGradient>
            
            <!-- Gradient for the crown -->
            <linearGradient id="crownGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#4169E1;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#0047AB;stop-opacity:1" />
            </linearGradient>
            
            <!-- Shadow filter -->
            <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
            <feDropShadow dx="2" dy="4" stdDeviation="3" flood-color="#000000" flood-opacity="0.3"/>
            </filter>
        </defs>
        
        <!-- Background circle for elegance -->
        <circle cx="200" cy="60" r="55" fill="none" stroke="url(#blueGradient)" stroke-width="1" opacity="0.3"/>
        
        <!-- Crown above the text -->
        <g transform="translate(200, 25)">
            <!-- Crown base -->
            <path d="M -20 0 L 20 0 L 18 -8 L 12 -5 L 6 -12 L 0 -8 L -6 -12 L -12 -5 L -18 -8 Z" 
                fill="url(#crownGradient)" 
                filter="url(#shadow)"/>
            <!-- Crown jewels -->
            <circle cx="0" cy="-6" r="2" fill="#FF0000" opacity="0.8"/>
            <circle cx="-8" cy="-3" r="1.5" fill="#0000FF" opacity="0.8"/>
            <circle cx="8" cy="-3" r="1.5" fill="#00FF00" opacity="0.8"/>
        </g>
        
        <!-- Main text "Prince" -->
        <text x="200" y="70" 
                font-family="serif" 
                font-size="32" 
                font-weight="bold" 
                text-anchor="middle" 
                fill="url(#blueGradient)" 
                filter="url(#shadow)">Prince</text>
        
        <!-- "AK" text with different styling -->
        <text x="200" y="95" 
                font-family="sans-serif" 
                font-size="18" 
                font-weight="600" 
                text-anchor="middle" 
                fill="#8B4513" 
                letter-spacing="3px">AK</text>
        
        <!-- Decorative flourishes -->
        <g stroke="url(#blueGradient)" stroke-width="1.5" fill="none" opacity="0.6">
            <!-- Left flourish -->
            <path d="M 120 60 Q 140 50 160 60 Q 140 70 120 60"/>
            <!-- Right flourish -->
            <path d="M 280 60 Q 260 50 240 60 Q 260 70 280 60"/>
        </g>
        
        <!-- Small decorative dots -->
        <circle cx="130" cy="60" r="1.5" fill="url(#blueGradient)" opacity="0.7"/>
        <circle cx="270" cy="60" r="1.5" fill="url(#blueGradient)" opacity="0.7"/>
    </svg>
</template>
