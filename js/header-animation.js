// d3 animation for header
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
