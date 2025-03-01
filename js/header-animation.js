// d3 animation for header
/*
document.addEventListener('DOMContentLoaded', function() {
  const width = document.getElementById('header-animation').offsetWidth;
  const height = document.getElementById('header-animation').offsetHeight; // Adjust height as needed
  
  const svg = d3.select('#header-animation')
    .append('svg')
    .attr('width', width)
    .attr('height', height)
    .style('background', 'transparent');
  
  // Generate random data points
  function generateData() {
    return Array.from({length: 50}, (_, i) => ({
      x: i * (width / 49),
      y: height/2 + Math.cos(i/5) * 50 + Math.random() * 20
    }));
  }
  
  // Create line generator
  const line = d3.line()
    .x(d => d.x)
    .y(d => d.y)
    .curve(d3.curveMonotoneX);
  
  // Add the path with initial data
  const path = svg.append('path')
    .datum(generateData())
    .attr('fill', 'none')
    .attr('stroke', '#2196F3') // Change color as needed
    .attr('stroke-width', 2)
    .attr('d', line);

});
*/
/*
// Create a self-executing function to avoid global scope pollution
(function() {
  function genPoints(amtW, amtH, w, h) {
    const arr = [];
    const push2 = (x1, y1, x2, y2) => {
      arr.push([[x1, y1], [x2, y2]]);
    };
    
    for(let y = 0; y <= amtH; y++) {
      for(let x = 0; x <= amtW; x++) {
        const xPos = x * w;
        const yPos = y * h;
        const dir = Math.floor(Math.random() * 7); // Replace three.js randInt
        
        if(dir === 0) {
          push2(xPos, yPos, xPos + w, yPos); // top
        } else if(dir === 1) {
          push2(xPos + w, yPos, xPos + w, yPos + h); // right
        } else if(dir === 2) {
          push2(xPos, yPos + h, xPos + w, yPos + h); // bottom
        } else if(dir === 3) {
          push2(xPos, yPos, xPos, yPos + h); // left
        } else if(dir === 4) {
          push2(xPos, yPos, xPos + w, yPos + h); // diag top left to bottom right
        } else if(dir === 5) {
          push2(xPos, yPos + h, xPos + w, yPos); // diag bottom left to top right
        }
        // dir === 6 means no line
      }
    }
    return arr;
  }

  function createRandomLines(containerId, options = {}) {
    const {
      width = 800,
      height = 600,
      wAmount = 10,
      hAmount = 10,
      reRender = false
    } = options;

    const wrapper = d3.select(`#${containerId}`);
    let currentWidth = width;

    // Clear any existing SVG
    wrapper.selectAll("*").remove();

    // Create new SVG
    const svg = d3.select(`#${containerId}`)
      .append("svg")
      .attr("width", width)
      .attr("height", height);

    // Calculate chunk sizes
    const chunkWidth = Math.floor(width / wAmount);
    const chunkHeight = Math.floor(height / hAmount);

    // Generate random color
    const color = `rgb(${Math.floor(Math.random() * 135 + 120)}, 
                      ${Math.floor(Math.random() * 255)}, 
                      ${Math.floor(Math.random() * 135 + 120)})`;

    // Generate points
    const points = genPoints(wAmount, hAmount, chunkWidth, chunkHeight);

    // Create line generator
    const lineGenerator = d3.line()
      .x(d => d[0])
      .y(d => d[1]);

    // Draw lines
    svg.selectAll("path")
      .data(points)
      .enter()
      .append("path")
      .attr("d", lineGenerator)
      .attr("stroke", color)
      .attr("fill", "none");
    
    // Optional: Add resize handling
    function handleResize() {
      const newWidth = parseInt(wrapper.style('width'));
      if (Math.abs(newWidth - width) > 50) {
        createRandomLines(containerId, {
          ...options,
          width: newWidth
        });
      }
      currentWidth = newWidth;
    }

    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }

  // Make the function available globally for WordPress
  window.createRandomLines = createRandomLines;
})();

*/

// Self-executing function to avoid global scope pollution
(function() {
  function genPoints(amtW, amtH, w, h) {
    const arr = [];
    const push2 = (x1, y1, x2, y2) => {
      arr.push([x1, y1, x2, y2]);   
    }
    
    for(let y = 0; y <= amtH; y++) {
      for(let x = 0; x <= amtW; x++) {
        const xPos = x * w;
        const yPos = y * h;
        const dir = Math.floor(Math.random() * 7);
        if(dir === 0) {
          push2(xPos, yPos, xPos + w, yPos); 
        } else if(dir === 1) {
          push2(xPos + w, yPos, xPos + w, yPos + h); 
        } else if(dir === 2) {
          push2(xPos, yPos + h, xPos + w, yPos + h); 
        } else if(dir === 3) {
          push2(xPos, yPos, xPos, yPos + h); 
        } else if(dir === 4) {
          push2(xPos, yPos, xPos + w, yPos + h); 
        } else if(dir === 5) {
          push2(xPos, yPos + h, xPos + w, yPos); 
        }
        // dir === 6 means no line
      }
    }
    return arr;
  }

  function createRandomLines(containerId, options = {}) {
    const {
      width = 800,
      height = 600,
      wAmount = 10,
      hAmount = 10
    } = options;

    // Create stage and layer
    const stage = new Konva.Stage({
      container: containerId,
      width: width,
      height: height
    });

    const layer = new Konva.Layer();
    stage.add(layer);

    // Generate random color
    const color = {
      r: Math.floor(Math.random() * 135 + 120),
      g: Math.floor(Math.random() * 255),
      b: Math.floor(Math.random() * 135 + 120)
    };

    // Calculate chunk sizes
    const chunkWidth = Math.floor(width / wAmount);
    const chunkHeight = Math.floor(height / hAmount);

    // Generate and draw points
    const points = genPoints(wAmount, hAmount, chunkWidth, chunkHeight);
    
    // Create lines in batch for better performance
    points.forEach(point => {
      if (point !== 0) {
        const line = new Konva.Line({
          points: point,
          stroke: `rgb(${color.r}, ${color.g}, ${color.b})`,
          strokeWidth: 1
        });
        layer.add(line);
      }
    });

    // Batch draw all lines at once
    layer.batchDraw();

    // Optional: Add resize handling with debounce for performance
    let resizeTimeout;
    function handleResize() {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(() => {
        const container = document.getElementById(containerId);
        const rect = container.getBoundingClientRect();
        if (Math.abs(rect.width - width) > 50) {
          stage.width(rect.width);
          stage.height(rect.height);
          createRandomLines(containerId, {
            ...options,
            width: rect.width,
            height: rect.height
          });
        }
      }, 250); // Debounce resize events
    }

    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }

  // Make function available globally for WordPress
  window.createRandomLines = createRandomLines;
})();

document.addEventListener('DOMContentLoaded', function() {
  const wrapp = document.querySelector('.site-branding');
  const rect = wrapp.getBoundingClientRect();
  createRandomLines('header-animation', {
    width: rect.width,
    height: rect.height,
    wAmount: 20,
    hAmount: 5
  });
});
