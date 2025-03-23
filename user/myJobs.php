<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: userLogin.php");
    exit;
}

include '../config/db_connection.php';

$user_id = $_SESSION['user_id'];

// Fetch jobs
$sql = "
    SELECT 
        jobs.id, 
        jobs.user_id, 
        jobs.job_title, 
        COALESCE(jobs.job_date, 'No Date Available') AS job_date,
        COALESCE(NULLIF(jobs.job_time, ''), 'No Time Available') AS job_time,
        jobs.location, 
        jobs.description, 
        jobs.budget, 
        jobs.images,
        jobs.status, 
        jobs.created_at,
        COALESCE(categories.name, 'No Category') AS category_name, 
        COALESCE(sub_categories.name, 'No Subcategory') AS sub_category_name,
        COUNT(offers.id) AS number_of_offers
    FROM jobs
    LEFT JOIN categories ON jobs.category_id = categories.id  
    LEFT JOIN sub_categories ON jobs.sub_category_id = sub_categories.id  
    LEFT JOIN offers ON jobs.id = offers.job_id
    WHERE jobs.user_id = ? AND jobs.status = 'open'
    GROUP BY jobs.id, categories.name, sub_categories.name
    ORDER BY jobs.created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$jobs = [];
while ($row = $result->fetch_assoc()) {
    $jobs[] = $row;
}
$stmt->close(); // Close statement after fetching

// Fetch offers if job_id is provided
$job_id = $_GET['job_id'] ?? null;
$offers = [];

if ($job_id) {
    $sql = "
        SELECT 
            offers.id AS offer_id,
            CONCAT(user.first_name, ' ', user.last_name) AS provider_name,
            user.profile_picture, 
            user.verification_status,  -- ✅ Fix: Use verification_status instead of is_verified
            offers.creation_time,
            offers.status AS offer_status,
            offers.offer_amount,
            offers.message,
            offers.completion_time
        FROM offers
        LEFT JOIN user ON offers.provider_id = user.id
        WHERE offers.job_id = ?;
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $offers_result = $stmt->get_result();

    while ($offer = $offers_result->fetch_assoc()) {
        $offers[] = $offer;
    }
    $stmt->close();
}

// Fetch user's own offers
$my_offers = [];
$sql = "
    SELECT 
        offers.id AS offer_id,
        offers.job_id,
        jobs.job_title,
        jobs.location,
        jobs.budget,
        offers.offer_amount,
        offers.status AS offer_status,
        offers.message,
        offers.completion_time,
        offers.creation_time,
        CONCAT(user.first_name, ' ', user.last_name) AS employer_name
    FROM offers
    JOIN jobs ON offers.job_id = jobs.id
    JOIN user ON jobs.user_id = user.id
    WHERE offers.provider_id = ? AND offers.status = 'accepted'
    ORDER BY offers.creation_time DESC;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$my_offers_result = $stmt->get_result();

while ($offer = $my_offers_result->fetch_assoc()) {
    $my_offers[] = $offer;
}
$stmt->close();

// Fetch completed jobs
$completed_jobs = [];
$sql = "
    SELECT 
        cj.id,
        cj.job_id,
        cj.offer_id,
        cj.provider_id,
        cj.user_id AS employer_id,
        jobs.job_title,
        jobs.location,
        offers.offer_amount,
        offers.completion_time,
        cj.completed_at,
        cj.rating,
        cj.review,
        CONCAT(user.first_name, ' ', user.last_name) AS employer_name,
        user.email AS employer_email
    FROM completed_jobs cj
    JOIN jobs ON cj.job_id = jobs.id
    JOIN offers ON cj.offer_id = offers.id
    JOIN user ON cj.user_id = user.id
    WHERE cj.provider_id = ?
    ORDER BY cj.completed_at DESC;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$completed_jobs_result = $stmt->get_result();

while ($job = $completed_jobs_result->fetch_assoc()) {
    $completed_jobs[] = $job;
}
$stmt->close();

// Fetch user details
$user = null;
$sql = "SELECT profile_picture, verification_status FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Ensure default values if null
$profilePicture = $user['profile_picture'] ?? 'default_profile.png';
$verificationStatus = $user['verification_status'] ?? 'unverified';

$conn->close();
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Jobs - QuickFix</title>
    <link rel="icon" href="../img/logo1.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

    body {
      font-family: 'Montserrat', sans-serif;
    }
  </style>
</head>
<?php include './userHeader.php'; ?>

<script>
    function jobDashboard() {
        return {
            jobsPosted: <?php echo json_encode($jobs); ?>,
            selectedJob: null,
            jobDetails: {},
            offers: <?php echo json_encode($offers); ?>,
            loading: true,
            isEmployer: true,
            showModal: false,
            selectedOffer: {},
            enlargedImage: null,
            showingOffers: false,
            currentTab: 'open',
            ongoingJobs: [],
            in_progress_jobs: [],

            async init() {
                await this.loadInProgressJobs();
                await this.loadCompletedJobs();
            },



            toggleRole() {
                this.isEmployer = !this.isEmployer;
            },

            viewDetails(job) {
                if (this.currentTab !== 'open') return;

                if (this.selectedJob && this.selectedJob.id === job.id) {
                    this.selectedJob = null;
                    this.showingOffers = false;
                    this.offers = [];
                } else {
                    this.selectedJob = job;
                    this.loadJobDetails(job.id);
                    this.scrollToJobDetails();
                }
            },


            viewOffers() {
                if (this.currentTab !== 'open' || !this.selectedJob) return;
                if (!this.selectedJob) return;

                if (this.selectedJob.number_of_offers == 0) {
                    alert("No offers available for this job yet.");
                    return;
                }

                if (this.showingOffers) {
                    this.showingOffers = false;
                    this.offers = [];
                } else {
                    this.showingOffers = true;
                    fetch(`getOffers.php?job_id=${this.selectedJob.id}`)
                        .then(response => response.json())
                        .then(data => {
                            this.offers = Array.isArray(data) ? data : [];
                        })
                        .catch(error => {
                            console.error("Error fetching offers:", error);
                            this.showingOffers = false;
                        });

                    this.scrollToOffers();
                }
            },

            loadJobDetails(jobId) {
                fetch(`getJobDetails.php?job_id=${jobId}`)
                    .then(response => response.json())
                    .then(data => {
                        this.jobDetails = data;
                        this.jobDetails.images = this.jobDetails.images ? this.jobDetails.images.split(',') : [];
                    })
                    .catch(error => {
                        console.error('Error fetching job details:', error);
                    });
            },

            loadOffers(jobId) {
                fetch(`getOffers.php?job_id=${jobId}`)
                    .then(response => response.json())
                    .then(data => {
                        this.offers = data || [];
                        this.showingOffers = this.offers.length > 0;
                    })
                    .catch(error => {
                        console.error('Error fetching offers:', error);
                        this.offers = [];
                    });
            },

            scrollToJobDetails() {
                setTimeout(() => {
                    let jobDetailsSection = document.getElementById('jobDetailsSection');
                    if (jobDetailsSection) {
                        let headerOffset = 80;
                        let elementPosition = jobDetailsSection.getBoundingClientRect().top + window.scrollY;
                        let offsetPosition = elementPosition - headerOffset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: "smooth"
                        });
                    }
                }, 300);
            },

            toggleOffers() {
                if (this.showingOffers) {
                    this.showingOffers = false;
                } else if (this.selectedJob) {
                    this.showingOffers = true;

                    fetch(`getOffers.php?job_id=${this.selectedJob.id}`)
                        .then(response => response.json())
                        .then(data => {
                            this.offers = Array.isArray(data) ? data : [];
                            if (this.offers.length === 0) {
                                setTimeout(() => alert("No offers available for this job yet."), 200);
                                this.showingOffers = false;
                            }
                        })
                        .catch(error => {
                            console.error("Error fetching offers:", error);
                            this.showingOffers = false;
                        });

                    this.scrollToOffers();
                }
            },

            scrollToOffers() {
                setTimeout(() => {
                    let offersSection = document.getElementById('offersSection');
                    if (offersSection) {
                        let headerOffset = 80;
                        let elementPosition = offersSection.getBoundingClientRect().top + window.scrollY;
                        let offsetPosition = elementPosition - headerOffset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: "smooth"
                        });
                    }
                }, 300);
            },

            acceptOffer() {
    if (!this.selectedOffer.offer_id || !this.selectedJob.id) {
        Swal.fire({
            title: "Error!",
            text: "Invalid offer or job.",
            icon: "error",
            confirmButtonColor: "#d33"
        });
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to accept this offer?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, accept it!",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('update_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    offer_id: this.selectedOffer.offer_id,
                    job_id: this.selectedJob.id
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: "Accepted!",
                        text: "Offer accepted successfully!",
                        icon: "success",
                        confirmButtonColor: "#28a745"
                    }).then(() => {
                        this.showModal = false;
                        this.loadOffers(this.selectedJob.id);
                        this.selectedJob.status = "In Progress";
                        
                        // ✅ Save active tab as "in_progress" before reloading
                        localStorage.setItem("activeTab", "in_progress");
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: data.message || "Something went wrong.",
                        icon: "error",
                        confirmButtonColor: "#d33"
                    });
                }
            })
            .catch(error => {
                console.error("Error:", error);
                Swal.fire({
                    title: "Error!",
                    text: "Something went wrong. Please try again.",
                    icon: "error",
                    confirmButtonColor: "#d33"
                });
            });
        }
    });
},



            openOfferModal(offer) {
                this.selectedOffer = offer;
                this.showModal = true;


            },

            updateJobStatus(jobId, newStatus) {
                console.log("Updating job status:", jobId, newStatus);

                fetch('update-job-status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            jobId: jobId,
                            status: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Server Response:", data);
                        if (data.success) {
                            console.log("Status updated successfully!");
                            this.fetchJobs();
                        } else {
                            console.error("Update failed:", data.message);
                        }
                    })
                    .catch(error => console.error("Fetch error:", error));
            },



            closeOfferModal() {
                this.showModal = false;
                this.selectedOffer = {};
            },

            enlargeImage(image) {
                this.enlargedImage = image;
            },

            closeImagePreview() {
                this.enlargedImage = null;
            }
        };
    }
</script>

<script>
    function jobTable() {
        return {
            // Job Lists
            in_progress_jobs: [],
            completed_jobs: [],
            my_offers: [],
            showingMyOffers: true,

            // Chat
            isChatOpen: false,
            chatMessages: [],
            newMessage: "",
            chatJobId: null,
            chatProviderId: null,
            chatProviderName: "",

            // User Details
            userId: <?= $_SESSION['user_id']; ?>,

            // Modals
            isImageModalOpen: false,
            imageModalSrc: "",
            showConfirmModal: false,
            showReviewModal: false,
            showJobDetailsModal: false,
            selectedJob: null,

            // Review
            reviewRating: null,
            reviewText: "",

            async init() {
                console.log("Job Table Initialized!");
                await this.fetchAcceptedOffers();
                this.fetchJobs();
                this.fetchMyOffers();
            },

            async fetchAcceptedOffers() {
                try {
                    const response = await fetch('/Quickfix/public/api/fetch_accepted_offers.php');
                    const data = await response.json();

                    if (Array.isArray(data)) {
                        this.my_offers = data;
                    } else {
                        console.error("Invalid data format:", data);
                    }
                } catch (error) {
                    console.error("Error fetching offers:", error);
                }
            },

            fetchJobs() {
                fetch('fetch_in_progress_jobs.php', {
                        cache: "no-store"
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.in_progress_jobs = data.map(job => ({
                            ...job,
                            showChat: false
                        }));
                    })
                    .catch(error => console.error("Error fetching in-progress jobs:", error));

                fetch('fetch_completed_jobs.php', {
                        cache: "no-store"
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.completed_jobs = data.map(job => ({
                            ...job,
                            assignedTo: job.provider_name
                        }));
                    })
                    .catch(error => console.error("Error fetching completed jobs:", error));
            },

            fetchMyOffers() {
                fetch('useroffers.php', {
                        cache: "no-store"
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.my_offers = Array.isArray(data) ? data : [];
                    })
                    .catch(error => console.error("Error fetching my offers:", error));
            },

            confirmCompletion(job) {
    this.selectedJob = job;
    this.showConfirmModal = true;
},

markJobCompleted() {
    if (!this.selectedJob) return;

    fetch('mark_job_completed.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            job_id: this.selectedJob.id
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            this.in_progress_jobs = this.in_progress_jobs.filter(job => job.id !== this.selectedJob.id);
            this.completed_jobs.push(this.selectedJob);
            this.showConfirmModal = false;

            // ✅ Show success message using SweetAlert
            Swal.fire({
                title: "Job Completed!",
                text: "The job has been marked as completed successfully.",
                icon: "success",
                confirmButtonColor: "#28a745"
            }).then(() => {
                // ✅ Save active tab before reloading
                localStorage.setItem("activeTab", "completed");
                location.reload(); // 🔄 Reload the page
            });

        } else {
            console.error("Error from server:", data.error);
            Swal.fire({
                title: "Error!",
                text: data.error || "Something went wrong.",
                icon: "error",
                confirmButtonColor: "#d33"
            });
        }
    })
    .catch(error => {
        console.error("Error completing job:", error);
        Swal.fire({
            title: "Error!",
            text: "Something went wrong. Please try again.",
            icon: "error",
            confirmButtonColor: "#d33"
        });
    });
},

openReviewModal(job) {
                console.log("Opening Review Modal for Job:", job);
                this.selectedJob = job;
                this.reviewRating = '';
                this.reviewText = '';
                this.showReviewModal = true;
            },

            async submitReview() {
    if (!this.reviewRating || !this.reviewText) {
        Swal.fire({
            title: "Oops!",
            text: "Please provide both a rating and a review.",
            icon: "warning",
            confirmButtonColor: "#f39c12"
        });
        return;
    }

    try {
        let response = await fetch('submit_review.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                job_id: this.selectedJob.id,
                rating: this.reviewRating,
                review: this.reviewText
            })
        });

        let result = await response.json();
        console.log("Server Response:", result);

        if (result.success) {
            this.selectedJob.rating = this.reviewRating;
            this.selectedJob.review = this.reviewText;
            this.showReviewModal = false;

            // ✅ Show success message using SweetAlert
            Swal.fire({
                title: "Review Submitted!",
                text: "Thank you for your feedback.",
                icon: "success",
                confirmButtonColor: "#28a745"
            });

        } else {
            Swal.fire({
                title: "Error!",
                text: result.message || "Something went wrong.",
                icon: "error",
                confirmButtonColor: "#d33"
            });
        }
    } catch (error) {
        console.error("Error submitting review:", error);
        Swal.fire({
            title: "Error!",
            text: "Failed to submit review. Please try again.",
            icon: "error",
            confirmButtonColor: "#d33"
        });
    }
},


validateRating() {
            // ✅ Allow only numbers 1-5
            if (!/^[1-5]$/.test(this.reviewRating)) {
                this.reviewRating = "";
            }
        },


        
            openJobDetails(job) {
                this.selectedJob = job;
                this.showJobDetailsModal = true;
            },

            openChat(jobId, providerId, providerName) {
                this.chatJobId = jobId;
                this.chatProviderId = providerId;
                this.chatProviderName = providerName;
                this.isChatOpen = true;

                this.fetchMessages();
                this.startRealtimeUpdates();
            },

            fetchMessages() {
                if (!this.chatJobId) return;

                fetch("fetch_messages.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            id: this.chatJobId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.chatMessages = data.messages;
                            this.scrollToBottom();
                        }
                    })
                    .catch(error => console.error("Error fetching messages:", error));
            },

            sendMessage() {
                if (this.newMessage.trim() === "" && !this.imageFile) {
                    alert("Message or image is required.");
                    return;
                }

                let formData = new FormData();
                formData.append("data", JSON.stringify({
                    id: this.chatJobId,
                    message: this.newMessage
                }));
                if (this.imageFile) {
                    formData.append("image", this.imageFile);
                }

                fetch("send_message.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.newMessage = "";
                            this.imageFile = null;
                            this.imagePreview = null;
                            this.fetchMessages();
                        } else {
                            alert("Error: " + data.error);
                        }
                    })
                    .catch(error => console.error("Error sending message:", error));
            },

            handleFileUpload(event) {
                let file = event.target.files[0];
                if (!file) return;

                this.imageFile = file;
                let reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            openImageModal(src) {
                this.imageModalSrc = src;
                this.isImageModalOpen = true;
            },

            startRealtimeUpdates() {
                setInterval(() => {
                    if (this.isChatOpen) {
                        this.fetchMessages();
                    }
                }, 3000);
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    let container = document.getElementById("chat-container");
                    if (container) container.scrollTop = container.scrollHeight;
                });
            },

            formatTimestamp(timestamp) {
                let date = new Date(timestamp);
                let now = new Date();
                let isToday = date.toDateString() === now.toDateString();

                let hours = date.getHours() % 12 || 12;
                let minutes = date.getMinutes().toString().padStart(2, "0");
                let ampm = date.getHours() >= 12 ? "PM" : "AM";

                return isToday ?
                    `${hours}:${minutes} ${ampm}` :
                    `${date.toLocaleDateString()} ${hours}:${minutes} ${ampm}`;
            }
        };
    }
</script>

<script>
    function acceptedOffers() {
        return {
            offers: [],
            init() {
                fetch('fetch_accepted_offers.php')
                    .then(response => response.json())
                    .then(data => {
                        console.log("Fetched Offers (Raw Data):", data);
                        this.offers = JSON.parse(JSON.stringify(data));
                        console.log("Processed Offers (Plain Array):", this.offers);
                    })
                    .catch(error => {
                        console.error("Error fetching offers:", error);
                    });
            },
            openChat(jobId, contactName) {
                alert(`Opening chat with ${contactName} for Job ID: ${jobId}`);
            }
        };
    }
</script>

<script>
    function completedJobs() {
        return {
            completed_jobs: [],
            loading: true,

            async init() {
                try {
                    const response = await fetch('fetch_cjobs_provider.php');
                    const data = await response.json();
                    console.log("Fetched Completed Jobs Data (Raw):", data);

                    data.forEach((job, index) => {
                        job.employer_name = job.employer_name || 'Unknown Employer';
                        job.rating = job.rating !== null ? job.rating : 'N/A';
                        job.review = job.review || 'No review provided';
                    });

                    this.completed_jobs = data;
                    console.log("Processed Completed Jobs:", this.completed_jobs);

                } catch (error) {
                    console.error("Error fetching completed jobs:", error);
                    this.completed_jobs = [];
                } finally {
                    this.loading = false;
                }
            },

            formatDate(dateString) {
                const options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                return new Date(dateString.replace(' ', 'T')).toLocaleDateString('en-US', options);
            },

            showJobDetails(job) {
                this.selectedJob = job;
                this.isJobModalVisible = true;
            }
        };
    }

    document.addEventListener("DOMContentLoaded", function () {
    const activeTab = localStorage.getItem("activeTab") || "in_progress"; 
    document.getElementById(activeTab)?.classList.add("tab-active");
});

</script>


<body class="bg-gray-100" x-data="jobDashboard()" x-init="init()">
    <div class="w-full max-w-5xl mx-auto p-3 sm:p-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-blue-800 mb-4 sm:mb-6">My jobs</h1>

        <div class="mb-4 flex flex-wrap">
            <button class="px-3 py-2 sm:px-4 sm:py-2 mr-2 rounded-lg text-sm sm:text-base" :class="isEmployer ? 'bg-blue-800 text-white' : 'bg-gray-200 text-gray-700'" @click="toggleRole()">Employer View</button>
            <button class="px-3 py-2 sm:px-4 sm:py-2 rounded-lg text-sm sm:text-base" :class="!isEmployer ? 'bg-blue-800 text-white' : 'bg-gray-200 text-gray-700'" @click="toggleRole()">Job Seeker View</button>
        </div>

        <div class="flex flex-wrap space-x-2 sm:space-x-4 border-b pb-3 mb-4 sm:mb-6 overflow-x-auto">
            <button class="px-3 py-1 sm:px-4 sm:py-2 whitespace-nowrap text-sm sm:text-base" :class="currentTab === 'open' ? 'text-blue-700 font-semibold border-b-2 border-blue-700' : 'text-gray-500 hover:text-blue-700'" @click="currentTab = 'open'">Open</button>
            <button class="px-3 py-1 sm:px-4 sm:py-2 whitespace-nowrap text-sm sm:text-base" :class="currentTab === 'in_progress' ? 'text-blue-700 font-semibold border-b-2 border-blue-700' : 'text-gray-500 hover:text-blue-700'" @click="currentTab = 'in_progress'">In Progress</button>
            <button class="px-3 py-1 sm:px-4 sm:py-2 whitespace-nowrap text-sm sm:text-base" :class="currentTab === 'completed' ? 'text-blue-700 font-semibold border-b-2 border-blue-700' : 'text-gray-500 hover:text-blue-700'" @click="currentTab = 'completed'">Completed</button>
        </div>

        <div x-show="currentTab === 'open' && isEmployer" class="bg-white p-3 sm:p-4 shadow-md rounded-md overflow-x-auto">
            <h2 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Jobs You Posted</h2>
            <div class="overflow-x-auto -mx-3 sm:mx-0">
                <table class="w-full border border-gray-300 min-w-[640px]">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border p-1 sm:p-2 text-xs sm:text-sm">Job Title</th>
                            <th class="border p-1 sm:p-2 text-xs sm:text-sm">Date Posted</th>
                            <th class="border p-1 sm:p-2 text-xs sm:text-sm">Budget</th>
                            <th class="border p-1 sm:p-2 text-xs sm:text-sm">Offers</th>
                            <th class="border p-1 sm:p-2 text-xs sm:text-sm">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-if="jobsPosted.length === 0">
                            <tr>
                                <td colspan="6" class="text-center p-2 sm:p-4 text-gray-500 text-xs sm:text-sm">No jobs posted yet. Start by posting a job!</td>
                            </tr>
                        </template>

                        <template x-for="job in jobsPosted" :key="job.id">
                            <tr class="text-center border bg-white hover:bg-gray-100 transition">
                                <td class="border p-2 sm:p-3 font-semibold text-gray-700 text-xs sm:text-sm" x-text="job.job_title"></td>
                                <td class="border p-2 sm:p-3 text-gray-600 text-xs sm:text-sm" x-text="job.created_at"></td>
                                <td class="border p-2 sm:p-3 font-bold text-blue-800 text-xs sm:text-sm" x-text="'₱' + job.budget"></td>
                                <td class="border p-2 sm:p-3 text-blue-600 font-semibold text-xs sm:text-sm" x-text="job.number_of_offers"></td>
                                <td class="border p-2 sm:p-3">
                                    <button @click="viewDetails(job)"
                                        class="px-2 py-1 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold rounded transition"
                                        :class="selectedJob && selectedJob.id === job.id ? 'bg-blue-500 text-white' : 'bg-blue-500 text-white hover:bg-blue-800'">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="selectedJob && currentTab === 'open'" id="jobDetailsSection"
            class="bg-white mt-4 sm:mt-6 p-4 sm:p-6 shadow-lg rounded-xl border border-gray-200 w-full relative overflow-hidden">

            <div class="absolute inset-0 opacity-5 bg-gradient-to-br from-blue-500 to-blue-300 rounded-xl"></div>

            <div class="flex justify-between items-center mb-3 relative z-10 flex-wrap">
                <h2 class="text-xl sm:text-3xl font-bold text-blue-700">Job Details</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1 sm:mt-0" x-text="selectedJob.created_at"></p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 border-t border-gray-300 pt-4 relative z-10">
                <div>
                    <p class="text-sm sm:text-base font-semibold text-gray-600">Title</p>
                    <p class="text-lg sm:text-2xl font-semibold text-gray-900" x-text="selectedJob.job_title"></p>
                </div>
                <div>
                    <p class="text-sm sm:text-base font-semibold text-gray-600">Job Date</p>
                    <p class="text-base sm:text-xl text-gray-800" x-text="selectedJob.job_date"></p>
                </div>

                <div class="col-span-1 sm:col-span-2 flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                    <div class="w-full sm:w-1/2 mb-3 sm:mb-0">
                        <p class="text-sm sm:text-base font-semibold text-gray-600">Category</p>
                        <p class="text-base sm:text-lg text-gray-800 bg-blue-100 px-3 py-1 rounded-lg inline-block" x-text="selectedJob.category_name"></p>
                    </div>
                    <div class="w-full sm:w-1/2">
                        <p class="text-sm sm:text-base font-semibold text-gray-600">Subcategory</p>
                        <p class="text-base sm:text-lg text-gray-800 bg-blue-100 px-3 py-1 rounded-lg inline-block" x-text="selectedJob.sub_category_name"></p>
                    </div>
                </div>

                <div>
                    <p class="text-sm sm:text-base font-semibold text-gray-600">Time</p>
                    <p class="text-base sm:text-lg text-gray-800" x-text="selectedJob.job_time || 'No Time Available'"></p>
                </div>
                <div>
                    <p class="text-sm sm:text-base font-semibold text-gray-600">Location</p>
                    <p class="text-base sm:text-lg text-gray-800" x-text="selectedJob.location"></p>
                </div>
                <div>
                    <p class="text-sm sm:text-base font-semibold text-gray-600">Budget</p>
                    <p class="text-base sm:text-xl text-blue-800 font-bold" x-text="'₱' + selectedJob.budget"></p>
                </div>
                <div>
                    <p class="text-sm sm:text-base font-semibold text-gray-600">Status</p>
                    <span :class="selectedJob.status == 'Open' ? 'bg-blue-100 text-blue-800 px-3 py-1 rounded-full' : 'bg-gray-200 text-gray-700 px-3 py-1 rounded-full'" x-text="selectedJob.status"></span>
                </div>
            </div>

            <div class="border-t border-gray-300 pt-4 mt-4 relative z-10">
                <p class="text-sm sm:text-base font-semibold text-gray-600">Description</p>
                <div class="p-3 sm:p-4 bg-gray-100 border-l-4 border-blue-400 rounded-md">
                    <p class="text-sm sm:text-base text-gray-800" x-text="selectedJob.description"></p>
                </div>
            </div>

            <div x-show="selectedJob.images" class="mt-4 sm:mt-6 relative z-10">
                <h3 class="text-base sm:text-lg font-semibold text-blue-700 mb-2 flex items-center">
                    <img src="../img/image-my-job.svg" alt="Job Images Icon" class="w-4 h-4 sm:w-5 sm:h-5 mr-2">
                    Job Images
                </h3>
                <div class="flex space-x-2 sm:space-x-4 overflow-x-auto p-2">
                    <template x-for="image in selectedJob.images.split(',')" :key="image">
                        <img :src="image.trim()"
                            class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-lg shadow-md border border-gray-300 cursor-pointer hover:scale-105 transition"
                            @click="enlargedImage = image.trim()">
                    </template>
                </div>
            </div>

            <div class="border-t border-gray-300 pt-4 mt-4 flex justify-between items-center relative z-10">
                <button @click="viewOffers()"
                    class="px-3 py-1 sm:px-5 sm:py-2 text-xs sm:text-base font-semibold rounded-lg transition shadow-md"
                    :class="showingOffers && selectedJob.number_of_offers > 0 ? 'bg-blue-500 text-white' : 'bg-blue-500 text-white hover:bg-blue-800'">
                    View Offers (<span x-text="selectedJob ? selectedJob.number_of_offers : 0"></span>)
                </button>
            </div>
        </div>

        <div x-show="enlargedImage"
            class="fixed inset-0 bg-black bg-opacity-75 flex justify-center items-center z-50 p-4"
            @click="closeImagePreview()"
            x-cloak>

            <div class="relative">
                <button class="absolute -top-3 -right-3 bg-black text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-gray-800 transition shadow-md"
                    @click="closeImagePreview()">
                    ✕
                </button>

                <img :src="enlargedImage"
                    class="max-w-[90vw] sm:max-w-[80vw] max-h-[70vh] sm:max-h-[80vh] object-contain rounded-lg shadow-lg border border-gray-300">
            </div>
        </div>
        <div x-show="offers.length > 0 && currentTab === 'open'" id="offersSection"
     class="bg-white mt-4 sm:mt-6 p-4 sm:p-6 shadow-md rounded-lg border border-gray-300 w-full">
    <h2 class="text-xl sm:text-2xl font-semibold text-blue-700 flex items-center mb-3 sm:mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 mr-2 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
        </svg>
        Offers Received
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 border-t border-gray-300 pt-4">
        <template x-for="offer in offers" :key="offer.offer_id">
            <div class="bg-gray-50 p-3 sm:p-4 rounded-lg border border-gray-300 shadow hover:shadow-lg transition cursor-pointer"
                 @click="openOfferModal(offer)">
                <div class="flex items-center space-x-2">
                    <!-- Profile Picture with Dynamic Border Color -->
                    <img :src="offer.profile_picture || '../img/default-avatar.png'" 
                         alt="Profile Pic" 
                         :class="{
                            'border-green-500': offer.verification_status === 'identity_verified',
                            'border-blue-500': offer.verification_status === 'fully_verified',
                            'border-red-500': offer.verification_status === 'unverified'
                         }"
                         class="w-10 h-10 rounded-full border object-cover">
                    
                    <div>
                        <!-- Clickable Provider Name -->
                        <a :href="'public_profile.php?user_id=' + offer.provider_id + '&source=myJobs.php'"
   class="text-base sm:text-lg font-semibold text-blue-700 hover:underline flex items-center"
   @click.stop>
   <span x-text="offer.provider_name"></span>

                            <!-- Verification Badge with Dynamic Color -->
                            <svg x-show="offer.verification_status !== 'unverified'"
                                 xmlns="http://www.w3.org/2000/svg" 
                                 class="w-5 h-5 ml-1" 
                                 fill="none" 
                                 viewBox="0 0 24 24" 
                                 stroke="currentColor" 
                                 stroke-width="2"
                                 :class="{
                                    'text-green-500': offer.verification_status === 'identity_verified',
                                    'text-blue-500': offer.verification_status === 'fully_verified'
                                 }">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                      d="M9 12l2 2 4-4m-6 8a9 9 0 100-18 9 9 0 000 18z" />
                            </svg>
                        </a>

                        <!-- Verification Status Badge -->
                        <span class="text-xs font-medium px-2 py-1 rounded-full"
                              :class="{
                                'bg-green-100 text-green-700': offer.verification_status === 'identity_verified',
                                'bg-blue-100 text-blue-700': offer.verification_status === 'fully_verified',
                                'bg-red-100 text-red-700': offer.verification_status === 'unverified'
                              }"
                              x-text="offer.verification_status.replace('_', ' ')">
                        </span>
                    </div>
                </div>

                <!-- Offer Details -->
                <p class="text-base sm:text-lg text-blue-800"><strong>Offer Amount:</strong> ₱<span x-text="offer.offer_amount"></span></p>
                <p class="text-base sm:text-lg"><strong>Date:</strong> <span x-text="offer.creation_time"></span></p>
                <p class="text-base sm:text-lg"><strong>Status:</strong>
                    <span :class="offer.offer_status === 'Pending' ? 'bg-yellow-100 text-yellow-700 px-2 py-1 rounded' : 'bg-yellow-100 text-yellow-700 px-2 py-1 rounded'" 
                          x-text="offer.offer_status">
                    </span>
                </p>
            </div>
        </template>
    </div>
</div>


        <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-4" x-cloak>
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow-lg w-full max-w-xs sm:max-w-md md:max-w-lg">
                <h2 class="text-lg sm:text-xl font-semibold text-blue-700 mb-3 sm:mb-4">Offer Details</h2>
                <p class="text-sm sm:text-base"><strong>Provider:</strong> <span x-text="selectedOffer.provider_name"></span></p>
                <p class="text-sm sm:text-base"><strong>Offer Amount:</strong> <span class="text-blue-800 font-semibold" x-text="'₱' + selectedOffer.offer_amount"></span></p>
                <p class="text-sm sm:text-base"><strong>Date:</strong> <span x-text="selectedOffer.creation_time"></span></p>
                <p class="text-sm sm:text-base"><strong>Message:</strong> <span x-text="selectedOffer.message"></span></p>
                <p class="text-sm sm:text-base"><strong>Status:</strong>
                    <span :class="selectedOffer.offer_status === 'Pending' ? 'bg-yellow-100 text-yellow-700 px-2 py-1 rounded' : 'bg-yellow-100 text-yellow-700 px-2 py-1 rounded'"
                        x-text="selectedOffer.offer_status">
                    </span>
                </p>

                <div class="mt-4 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                    <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition text-sm sm:text-base"
                        @click="closeOfferModal()">Close</button>

                    <button @click="acceptOffer()"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition text-sm sm:text-base">
                        Accept Offer
                    </button>
                </div>
            </div>
        </div>

        <div x-data="jobTable()" x-init="
    fetchJobs(); 
    currentTab = localStorage.getItem('activeTab') || 'in_progress';
">


            <div x-show="currentTab === 'in_progress' && isEmployer" class="bg-white p-3 sm:p-4 shadow-md rounded-md overflow-x-auto">
                <h2 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Jobs In Progress</h2>
                <div class="overflow-x-auto -mx-3 sm:mx-0">
                    <table class="w-full border border-gray-300 min-w-[640px]">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border p-1 sm:p-2 text-xs sm:text-sm">Job Title</th>
                                <th class="border p-1 sm:p-2 text-xs sm:text-sm">Job Date</th>
                                <th class="border p-1 sm:p-2 text-xs sm:text-sm">Job Time</th>
                                <th class="border p-1 sm:p-2 text-xs sm:text-sm">Offer Amount</th>
                                <th class="border p-1 sm:p-2 text-xs sm:text-sm">Assigned To</th>
                                <th class="border p-1 sm:p-2 text-xs sm:text-sm">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="in_progress_jobs.length === 0">
                                <tr>
                                    <td colspan="6" class="text-center p-2 sm:p-4 text-gray-500 text-xs sm:text-sm">No jobs in progress.</td>
                                </tr>
                            </template>

                            <template x-for="job in in_progress_jobs" :key="job.id">
                                <tr class="text-center border bg-white hover:bg-gray-100 transition">
                                    <td class="border p-2 sm:p-3 font-semibold text-gray-700 text-xs sm:text-sm" x-text="job.job_title"></td>
                                    <td class="border p-2 sm:p-3 text-gray-600 text-xs sm:text-sm" x-text="job.job_date"></td>
                                    <td class="border p-2 sm:p-3 text-gray-600 text-xs sm:text-sm" x-text="job.job_time"></td>
                                    <td class="border p-2 sm:p-3 font-bold text-gray-600 text-xs sm:text-sm" x-text="'₱' + job.offer_amount"></td>
                                    <td class="border p-2 sm:p-3 text-gray-600 font-semibold text-xs sm:text-sm" x-text="job.assigned_to"></td>
                                    <td class="border p-2 sm:p-3">
                                        <button @click="confirmCompletion(job)"
                                            class="px-2 py-1 sm:py-2 text-xs sm:text-sm rounded bg-green-500 text-white hover:bg-green-600 transition flex items-center mb-2 w-full justify-center">
                                            <img src="../img/done-my-job.svg" alt="Done Icon" class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2">
                                            Mark as Complete
                                        </button>
                                        <button @click="openChat(job.id, job.assigned_to_id, job.assigned_to)"
                                            class="px-2 py-1 sm:py-2 text-xs sm:text-sm rounded bg-gray-200 hover:bg-gray-300 transition flex items-center justify-center w-full">
                                            <img src="../img/chat-my-job.svg" alt="Chat Icon" class="w-4 h-4 sm:w-5 sm:h-5">
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div x-show="isChatOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4 z-50">
                <div class="bg-white rounded-lg shadow-md w-full max-w-xs sm:max-w-sm md:max-w-md p-3 sm:p-4">
                    <div class="flex justify-between items-center border-b pb-2">
                        <h2 class="text-base sm:text-lg font-bold" x-text="'Chat with ' + chatProviderName"></h2>
                        <button @click="isChatOpen = false" class="text-gray-500 hover:text-red-500">✖</button>
                    </div>

                    <input type="hidden" x-model="chatJobId">

                    <div class="h-48 sm:h-60 overflow-y-auto p-2 border my-2 rounded-lg bg-gray-100" id="chat-container">
                        <template x-for="message in chatMessages" :key="message.id">
                            <div class="mb-2 flex" :class="message.sender_id === userId ? 'justify-end' : 'justify-start'">
                                <div class="p-2 max-w-[75%] rounded-lg shadow-md"
                                    :class="message.sender_id === userId ? 'bg-blue-500 text-white' : 'bg-gray-300 text-black'">

                                    <span class="text-xs font-semibold block"
                                        :class="message.sender_id === userId ? 'text-white' : 'text-gray-700'"
                                        x-text="message.sender_name">
                                    </span>

                                    <p x-text="message.content" class="text-xs sm:text-sm"></p>

                                    <template x-if="message.image">
                                        <img :src="message.image"
                                            @click="openImageModal(message.image)"
                                            class="mt-1 w-32 sm:w-40 rounded-lg shadow-md cursor-pointer hover:opacity-80">
                                    </template>

                                    <span class="text-[8px] sm:text-[10px] block mt-1 text-gray-200"
                                        :class="message.sender_id === userId ? 'text-gray-200' : 'text-gray-500'"
                                        x-text="message.created_at">
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-2 flex items-center gap-1 sm:gap-2">
                        <label class="cursor-pointer flex items-center gap-1 sm:gap-2 bg-gray-200 px-2 sm:px-3 py-1 rounded-lg hover:bg-gray-300">
                            <img src="../img/camera-my-job.svg" alt="Camera Icon" class="w-4 h-4 sm:w-5 sm:h-5">
                            <input type="file" @change="handleFileUpload" accept="image/*" class="hidden">
                        </label>
                        <input type="text" x-model="newMessage"
                            class="flex-1 border p-1 sm:p-2 rounded-lg focus:ring-2 focus:ring-blue-400 text-xs sm:text-sm"
                            placeholder="Type a message..."
                            @keyup.enter="sendMessage()">

                        <button @click="sendMessage()"
                            class="px-2 sm:px-4 py-1 sm:py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 flex items-center justify-center text-xs sm:text-sm">
                            Send
                        </button>
                    </div>

                    <template x-if="imagePreview">
                        <div class="mt-2 text-center">
                            <p class="text-xs text-gray-500">Image Preview:</p>
                            <img :src="imagePreview" class="max-w-[100px] sm:max-w-[150px] rounded-lg shadow mx-auto">
                        </div>
                    </template>
                </div>
            </div>

            <div x-show="isImageModalOpen"
                class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
                <div class="relative">
                    <button @click="isImageModalOpen = false"
                        class="absolute top-2 right-2 text-white text-xl sm:text-2xl">✖</button>

                    <img :src="imageModalSrc"
                        class="max-w-[90vw] sm:max-w-[80vw] max-h-[60vh] sm:max-h-[70vh] rounded-lg shadow-lg">
                </div>
            </div>

           <!-- Confirmation Modal -->
<div x-show="showConfirmModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 p-4 z-50">
    <div class="bg-white p-4 sm:p-6 rounded shadow-lg w-full max-w-xs sm:max-w-sm">
        <h2 class="text-base sm:text-lg font-semibold mb-2">Confirm Completion</h2>
        <p class="text-sm sm:text-base">Are you sure you want to mark this job as completed?</p>

        <div class="mt-4 flex justify-end space-x-2">
            <button @click="showConfirmModal = false" class="px-3 sm:px-4 py-1 sm:py-2 bg-gray-400 text-white rounded text-xs sm:text-sm">Cancel</button>
            <button @click="markJobCompleted()" class="px-3 sm:px-4 py-1 sm:py-2 bg-green-500 text-white rounded text-xs sm:text-sm">Confirm</button>
        </div>
    </div>
</div>


            <div x-data="jobTable()" x-init="fetchJobs()" x-show="currentTab === 'completed' && isEmployer"
                class="bg-white p-4 sm:p-8 shadow-lg rounded-lg mx-auto w-full max-w-4xl">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 text-center">Completed Jobs Log</h2>

                <div class="space-y-4 sm:space-y-6">
                    <template x-if="completed_jobs.length === 0">
                        <p class="text-center text-gray-500 text-sm sm:text-base">No completed jobs.</p>
                    </template>

                    <template x-for="job in completed_jobs" :key="job.id">
                        <div class="relative bg-gray-50 p-3 sm:p-6 rounded-lg shadow-sm border border-gray-200">
                            <div class="absolute left-0 top-4 bottom-4 w-1 bg-blue-500 rounded-l"></div>

                            <div class="flex flex-col sm:flex-row sm:items-start gap-3 sm:gap-4">
                                <div class="flex-1">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-800" x-text="job.job_title"></h3>
                                    <p class="text-xs sm:text-sm text-gray-600">
                                        Completed on <span x-text="job.completed_at"></span> by
                                        <span class="text-blue-600 font-semibold" x-text="job.assigned_to"></span>
                                    </p>

                                    <div class="flex items-center gap-2">
                                        <span x-text="job.rating ? job.rating + ' ⭐ - ' + job.review : 'No Review'"></span>
                                        <button x-show="!job.rating" @click="openReviewModal(job)"
                                            class="px-2 sm:px-3 py-0.5 sm:py-1 text-xs bg-green-500 text-white rounded-full hover:bg-green-600 transition flex items-center gap-2">
                                            <img src="../img/review-my-job.svg" alt="Review Icon" class="w-5 h-5">
                                            Add Review
                                        </button>
                                    </div>
                                </div>

                                <div class="flex space-x-2">
                                    <button @click="openJobDetails(job)"
                                        class="px-3 sm:px-5 py-1 sm:py-2 bg-gray-200 text-gray-800 rounded-full hover:bg-gray-300 transition text-xs sm:text-base flex items-center gap-2">
                                        <img src="../img/details-my-job.svg" alt="Details Icon" class="w-5 h-5">
                                        Details
                                    </button>
                                    <button @click="openChat(job.id, job.provider_id)"
                                        class="px-3 sm:px-5 py-1 sm:py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition shadow-md text-xs sm:text-base flex items-center gap-2">
                                        <img src="../img/chat-my-job.svg" alt="Chat Icon" class="w-5 h-5">
                                        Chat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>


             <!-- Review Modal -->
<div x-show="showReviewModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4 z-50">
    <div class="bg-white p-4 sm:p-5 rounded shadow-lg w-full max-w-xs sm:max-w-sm">
        <h3 class="text-base sm:text-lg font-semibold mb-3">Submit Your Review</h3>

        <!-- ✅ Rating Input with Validation -->
        <label class="block mb-2 text-sm sm:text-base">Rating (1-5):</label>
        <input type="text" 
               x-model="reviewRating"
               @input="validateRating"
               class="w-full p-1 sm:p-2 border rounded mb-3 text-sm sm:text-base"
               maxlength="1"
               placeholder="1-5">

        <label class="block mb-2 text-sm sm:text-base">Review:</label>
        <textarea x-model="reviewText" rows="3"
            class="w-full p-1 sm:p-2 border rounded text-sm sm:text-base"></textarea>

        <div class="flex justify-end mt-3">
            <button @click="showReviewModal = false"
                class="mr-2 px-2 sm:px-3 py-1 bg-gray-400 text-white rounded hover:bg-gray-500 text-xs sm:text-sm">
                Later
            </button>
            <button @click="submitReview()"
                class="px-2 sm:px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs sm:text-sm">
                Submit
            </button>
        </div>
    </div>
</div>


                <div x-show="showJobDetailsModal"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 transition-opacity z-50"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                    <div class="bg-white rounded-lg shadow-lg w-full max-w-xs sm:max-w-md border border-gray-300 
                transform transition-all scale-95 max-h-[80vh] overflow-hidden mt-8 sm:mt-16">

                        <div class="bg-gray-150 text-center border-b p-3 sm:p-4 sticky top-0 z-10">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Job Summary</h2>
                        </div>

                        <div class="p-3 sm:p-5 space-y-3 sm:space-y-4 text-gray-700 overflow-auto max-h-[50vh] sm:max-h-[60vh]">

                            <div class="border p-2 sm:p-3 rounded bg-gray-50">
                                <p class="text-xs sm:text-sm text-gray-500">Job Title</p>
                                <p class="text-base sm:text-lg font-semibold" x-text="selectedJob?.job_title"></p>
                            </div>

                            <div class="border p-2 sm:p-3 rounded bg-gray-50">
                                <p class="text-xs sm:text-sm text-gray-500">Location</p>
                                <p class="text-base sm:text-lg font-medium" x-text="selectedJob?.location"></p>
                            </div>

                            <div class="border p-2 sm:p-3 rounded bg-gray-50">
                                <p class="text-xs sm:text-sm text-gray-500">Description</p>
                                <p class="text-xs sm:text-base" x-text="selectedJob?.description"></p>
                            </div>

                            <hr class="border-gray-400">

                            <h3 class="text-base sm:text-lg font-semibold text-gray-800 text-center">Completion Info</h3>

                            <div class="flex justify-between text-sm sm:text-lg">
                                <p class="text-gray-500">Completed By:</p>
                                <p class="font-medium" x-text="selectedJob?.assigned_to"></p>
                            </div>

                            <div class="flex justify-between text-sm sm:text-lg">
                                <p class="text-gray-500">Completion Time:</p>
                                <p class="font-medium" x-text="selectedJob?.completion_time"></p>
                            </div>

                            <div class="flex justify-between text-sm sm:text-lg">
                                <p class="text-gray-500">Completed At:</p>
                                <p class="font-medium" x-text="selectedJob?.completed_at"></p>
                            </div>

                            <hr class="border-gray-400">

                            <h3 class="text-base sm:text-lg font-semibold text-gray-800 text-center">Payment Summary</h3>

                            <div class="flex justify-between text-base sm:text-xl font-bold">
                                <p>Total Paid:</p>
                                <p class="text-gray-600 font-mono" x-text="'₱' + selectedJob?.offer_amount"></p>
                            </div>
                        </div>

                        <div class="bg-gray-200 border-t p-3 sm:p-4 sticky bottom-0 z-10 flex justify-center">
                            <button @click="showJobDetailsModal = false"
                                class="px-4 sm:px-6 py-1 sm:py-2 bg-gray-400 text-gray-900 text-sm sm:text-lg rounded-full hover:bg-gray-500 transition">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="!isEmployer && currentTab === 'open'" class="bg-white p-3 sm:p-4 shadow-md rounded-md overflow-x-auto">
                <h2 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Pending offers</h2>

                <div class="overflow-x-auto -mx-3 sm:mx-0">
                    <table class="w-full border border-gray-300 min-w-[640px]" x-show="showingMyOffers">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border p-1 sm:p-2 text-xs sm:text-sm">Job Title</th>
                                <th class="border p-1 sm:p-2 text-xs sm:text-sm">Job Date</th>
                                <th class="border p-1 sm:p-2 text-xs sm:text-sm">Location</th>
                                <th class="border p-1 sm:p-2 text-xs sm:text-sm">Offer Amount</th>
                                <th class="border p-1 sm:p-2 text-xs sm:text-sm">Offer Status</th>
                                <th class="border p-1 sm:p-2 text-xs sm:text-sm">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="my_offers.length === 0">
                                <tr>
                                    <td colspan="6" class="text-center p-2 sm:p-4 text-gray-500 text-xs sm:text-sm">No offers made.</td>
                                </tr>
                            </template>

                            <template x-for="offer in my_offers" :key="offer.offer_id">
                                <tr class="text-center border bg-white hover:bg-gray-100 transition">
                                    <td class="border p-2 sm:p-3 font-semibold text-gray-700 text-xs sm:text-sm" x-text="offer.job_title"></td>
                                    <td class="border p-2 sm:p-3 text-gray-600 text-xs sm:text-sm" x-text="offer.job_date"></td>
                                    <td class="border p-2 sm:p-3 text-gray-600 text-xs sm:text-sm" x-text="offer.location"></td>
                                    <td class="border p-2 sm:p-3 font-bold text-blue-800 text-xs sm:text-sm" x-text="'₱' + offer.offer_amount"></td>
                                    <td class="border p-2 sm:p-3 text-yellow-300 font-semibold text-xs sm:text-sm" x-text="offer.offer_status"></td>
                                    <td class="border p-2 sm:p-3">
                                        <button @click="withdrawOffer(offer.offer_id)"
                                            class="px-2 py-1 text-xs sm:text-sm rounded bg-red-500 text-white hover:bg-red-600 transition">
                                            Withdraw offer
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div x-show="!isEmployer && currentTab === 'in_progress'"
                x-data="acceptedOffers()"
                x-init="init()"
                class="bg-gradient-to-br from-gray-100 to-gray-200 p-4 sm:p-6 rounded-lg shadow-md">

                <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 text-gray-800">In Progress Offers</h2>

                <template x-if="offers.length === 0">
                    <p class="text-center text-gray-500 text-base sm:text-lg">No accepted offers.</p>
                </template>

                <div class="grid gap-4 sm:gap-5">
                    <template x-for="offer in offers" :key="offer.id">
                        <div class="p-4 sm:p-6 border border-gray-300 rounded-lg bg-white shadow-sm hover:shadow-lg transition-all duration-300">
                            <div class="border-b pb-3 sm:pb-4 mb-3 sm:mb-4">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900" x-text="offer.job_title"></h3>
                                <p class="text-xs sm:text-sm text-gray-600"><strong>Employer:</strong> <span x-text="offer.employer_name"></span></p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 text-xs sm:text-sm text-gray-700">
                                <div>
                                    <p class="font-medium">Location</p>
                                    <p class="text-gray-800" x-text="offer.location"></p>
                                </div>
                                <div>
                                    <p class="font-medium">Budget</p>
                                    <p class="text-gray-800" x-text="'₱' + offer.budget"></p>
                                </div>
                                <div>
                                    <p class="font-medium">Offer Amount</p>
                                    <p class="text-gray-800" x-text="'₱' + offer.offer_amount"></p>
                                </div>
                                <div>
                                    <p class="font-medium">Completion Time</p>
                                    <p class="text-gray-800" x-text="offer.completion_time"></p>
                                </div>
                            </div>

                            <div class="mt-4 sm:mt-6 flex justify-end">
                                <button @click="openChat(offer.job_id, offer.employer_name)"
                                    class="px-3 sm:px-5 py-1 sm:py-2 text-xs sm:text-sm font-medium bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition-all duration-300">
                                    Chat Now
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div x-data="completedJobs()" x-init="init()" x-show="currentTab === 'completed' && !isEmployer"
                class="bg-gradient-to-br from-gray-100 to-gray-200 p-4 sm:p-6 rounded-lg shadow-md">

                <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 text-gray-800 text-center">Completed Jobs</h2>

                <template x-if="!loading && completed_jobs.length === 0">
                    <p class="text-center text-gray-500 text-base sm:text-lg">No completed jobs yet.</p>
                </template>

                <div class="space-y-4 sm:space-y-6">
                    <template x-for="(job, index) in completed_jobs" :key="index + '-' + job.id">
                        <div class="relative bg-gray-50 p-4 sm:p-6 rounded-lg shadow-sm border border-gray-200">

                            <div class="absolute left-0 top-4 bottom-4 w-1 bg-blue-500 rounded-l"></div>

                            <div class="flex flex-col sm:flex-row sm:items-start gap-3 sm:gap-4">
                                <div class="flex-1">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-800" x-text="job.job_title"></h3>
                                    <p class="text-xs sm:text-sm text-gray-600">
                                        Completed on <span x-text="job.completed_at"></span> by
                                        <span class="text-blue-600 font-semibold" x-text="job.assigned_to"></span>
                                    </p>

                                    <div class="mt-2 sm:mt-3 text-xs sm:text-sm">
                                        <span x-text="job.rating ? job.rating + ' ⭐ - ' + job.review : 'No Review'"></span>
                                        <button x-show="!job.rating" @click="openReviewModal(job)"
    class="px-2 sm:px-3 py-0.5 sm:py-1 text-xs bg-green-500 text-white rounded-full hover:bg-green-600 transition flex items-center gap-2">
    <img src="../img/review-my-job.svg" alt="Review Icon" class="w-5 h-5">
    Add Review
</button>

                                    </div>
                                </div>

                                <div class="flex space-x-2">
                                    <button @click="showJobDetails(job)"
                                        class="px-3 sm:px-5 py-1 sm:py-2 bg-gray-200 text-gray-800 rounded-full hover:bg-gray-300 transition text-xs sm:text-base flex items-center gap-2">
                                        <img src="../img/details-my-job.svg" alt="Details Icon" class="w-5 h-5">
                                        Details
                                    </button>
                                    <button @click="openChat(job.id, job.provider_id)"
                                        class="px-3 sm:px-5 py-1 sm:py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition shadow-md text-xs sm:text-base flex items-center gap-2">
                                        <img src="../img/chat-my-job.svg" alt="Chat Icon" class="w-5 h-5">
                                        Chat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="showReviewModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4 z-50">
    <div class="bg-white p-4 sm:p-5 rounded shadow-lg w-full max-w-xs sm:max-w-sm">
        <h3 class="text-base sm:text-lg font-semibold mb-3">Submit Your Review</h3>

        <label class="block mb-2 text-sm sm:text-base">Rating (1-5):</label>
        <input type="number" min="1" max="5" x-model="reviewRating"
            class="w-full p-1 sm:p-2 border rounded mb-3 text-sm sm:text-base">

        <label class="block mb-2 text-sm sm:text-base">Review:</label>
        <textarea x-model="reviewText" rows="3"
            class="w-full p-1 sm:p-2 border rounded text-sm sm:text-base"></textarea>

        <div class="flex justify-end mt-3">
            <button @click="showReviewModal = false"
                class="mr-2 px-2 sm:px-3 py-1 bg-gray-400 text-white rounded hover:bg-gray-500 text-xs sm:text-sm">
                Later
            </button>
            <button @click="submitReview()"
                class="px-2 sm:px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs sm:text-sm">
                Submit
            </button>
        </div>
    </div>
</div>


                <div x-show="isJobModalVisible"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 transition-opacity z-50"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                    <div class="bg-white rounded-lg shadow-lg w-full max-w-xs sm:max-w-md border border-gray-300 
                    transform transition-all scale-95 max-h-[80vh] overflow-hidden mt-8 sm:mt-16">

                        <div class="bg-gray-150 text-center border-b p-3 sm:p-4 sticky top-0 z-10">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Job Summary</h2>
                        </div>

                        <div class="p-3 sm:p-5 space-y-3 sm:space-y-4 text-gray-700 overflow-auto max-h-[50vh] sm:max-h-[60vh]">
                            <div class="border p-2 sm:p-3 rounded bg-gray-50">
                                <p class="text-xs sm:text-sm text-gray-500">Job Title</p>
                                <p class="text-base sm:text-lg font-semibold" x-text="selectedJob?.job_title"></p>
                            </div>

                            <div class="border p-2 sm:p-3 rounded bg-gray-50">
                                <p class="text-xs sm:text-sm text-gray-500">Employer Name</p>
                                <p class="text-base sm:text-lg font-medium" x-text="selectedJob?.employer_name"></p>
                            </div>

                            <div class="border p-2 sm:p-3 rounded bg-gray-50">
                                <p class="text-xs sm:text-sm text-gray-500">Location</p>
                                <p class="text-base sm:text-lg font-medium" x-text="selectedJob?.location"></p>
                            </div>

                            <div class="border p-2 sm:p-3 rounded bg-gray-50">
                                <p class="text-xs sm:text-sm text-gray-500">Description</p>
                                <p class="text-xs sm:text-base" x-text="selectedJob?.description"></p>
                            </div>

                            <hr class="border-gray-400">

                            <div class="flex justify-between text-sm sm:text-lg">
                                <p class="text-gray-500">Completed At:</p>
                                <p class="font-medium" x-text="selectedJob?.completed_at"></p>
                            </div>

                            <div class="flex justify-between text-sm sm:text-lg">
                                <p class="text-gray-500">Paid Amount:</p>
                                <p class="font-medium text-gray-600" x-text="'₱' + selectedJob?.offer_amount"></p>
                            </div>
                        </div>

                        <div class="bg-gray-200 border-t p-3 sm:p-4 sticky bottom-0 z-10 flex justify-center">
                            <button @click="isJobModalVisible = false"
                                class="px-4 sm:px-6 py-1 sm:py-2 bg-gray-400 text-gray-900 text-sm sm:text-lg rounded-full hover:bg-gray-500 transition">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>