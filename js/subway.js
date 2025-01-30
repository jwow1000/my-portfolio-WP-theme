const wrapper = document.getElementById("subway-container");

if( wrapper) {
  // Array to store player objects
  let players = [];
  
  // Function to initialize players
  function onYouTubeIframeAPIReady() {
    const links = [
      "spRQR7xBG3g",
      "-4kpwJKwZ8Q",
      "US-81APdmFM",
      "hawErPK95Ak",
      "bRrMaBHg8Ug",
      "1vIiw26kOrI",
      "PDeznrE5mVk",
      "4ZmNfeSgIpg",
      "xGTYk31Py3g",
    ];
  
    const wrapper = document.getElementById("subway-container");
  
    links.forEach((videoId, index) => {
      // Create a container for each video
      const container = document.createElement("div");
      container.id = `video-${index}`;
      container.classList.add("subway-videos");
      wrapper.appendChild(container);
  
      // Initialize the player
      players[index] = new YT.Player(`video-${index}`, {
        videoId: videoId,
        playerVars: {
          autoplay: 1, // Autoplay the video
          mute: 1, // Start muted
          rel: 0, // Disable related videos
          controls: 0, // Hide controls (optional)
        },
        events: {
          onReady: (event) => {
            // Autoplay the video when ready
            event.target.playVideo();
          },
        },
      });
    });
  }
  
  // Mute all videos
  document.getElementById("mute-button").addEventListener("click", () => {
    players.forEach((player) => {
      player.mute();
    });
  });
  
  // Unmute all videos
  document.getElementById("unmute-button").addEventListener("click", () => {
    players.forEach((player) => {
      player.unMute();
    });
  });

}
