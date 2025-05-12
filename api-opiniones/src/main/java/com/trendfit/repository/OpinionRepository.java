package com.trendfit.repository;

import com.trendfit.model.Opinion;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface OpinionRepository extends JpaRepository<Opinion, Long> {

    List<Opinion> findByProductIdOrderByDateDesc(Long productId);

    @Query("SELECT AVG(o.rating) FROM Opinion o")
    Double getGlobalAverageRating();

    @Query("SELECT o.productId, AVG(o.rating) as averageRating, COUNT(o.id) as numRatings " +
           "FROM Opinion o GROUP BY o.productId")
    List<Object[]> getProductRatings();
}